<?php
namespace App\Services;


use Carbon\Carbon;
use App\Services\ReferentielService;

use App\Repositories\PromotionRepository;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ReferentielRepository;

class PromotionService implements PromotionServiceInterface
{
    protected $promotionRepository;
    protected $referentielRepository;

    public function __construct(PromotionRepository $promotionRepository, ReferentielRepository $referentielRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->referentielRepository = $referentielRepository;
    }
    public function createPromotion($data)
    {
        $validator = Validator::make($data, [
            'libelle' => 'required|string|max:255',
            'date_debut' => 'required|date_format:d/m/Y',
            'date_fin' => 'required|date_format:d/m/Y|after:date_debut',
            'referentiels' => 'nullable|array',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $existingPromotions = $this->promotionRepository->getAllPromotions();
        foreach ($existingPromotions as $promotion) {
            if ($promotion['libelle'] === $data['libelle']) {
                throw new \Exception('Le libellé de la promotion doit être unique.');
            }
        }
    
        if (isset($data['photo'])) {
            $photoPath = $data['photo']->store('promotions', 'public');
            $data['photo'] = $photoPath;
        }
    
        $date_debut = Carbon::createFromFormat('d/m/Y', $data['date_debut']);
        $date_fin = Carbon::createFromFormat('d/m/Y', $data['date_fin']);
    
        $data['duree'] = $date_fin->diffInMonths($date_debut);
    
        $data['etat'] = 'Inactif';

        if (isset($data['referentiels'])) {
            $referentiels = [];
            foreach ($data['referentiels'] as $referentielId) {
                $referentiel = $this->referentielRepository->getReferentielById($referentielId);
                // dd($referentiel);
                if ($referentiel) {
                    $referentiels[] = $referentiel;
                }
            }
            $data['referentiels'] = $referentiels;
        }
    
        // if (isset($data['referentiels'])) {
        //     $referentiels = [];
        //     foreach ($data['referentiels'] as $referentielData) {
        //         if (is_int($referentielData)) {
        //             $referentiel = $this->referentielService->getReferentielById($referentielData);
        //             if ($referentiel) {
        //                 $referentiels[] = $referentiel;
        //             }
        //         } elseif (is_array($referentielData)) {
        //             $referentiel = $this->referentielService->createReferentiel($referentielData);
        //             $referentiels[] = $referentiel;
        //         }
        //     }
        //     $data['referentiels'] = $referentiels;
        // }
    
        return $this->promotionRepository->createPromotion($data);
    }
    
    public function updatePromotion($id, $data)
    {
        $promotion = $this->promotionRepository->getPromotionById($id);
    
        if (!$promotion) {
            throw new \Exception('Promotion non trouvée');
        }
    
        $validator = Validator::make($data, [
            'libelle' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date_format:d/m/Y',
            'date_fin' => 'nullable|date_format:d/m/Y|after:date_debut',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
    
        if (isset($data['photo'])) {
            $photoPath = $data['photo']->store('promotions', 'public');
            $data['photo'] = $photoPath;
        }
    
        if (isset($data['date_debut']) && isset($data['date_fin'])) {
            $date_debut = Carbon::createFromFormat('d/m/Y', $data['date_debut']);
            $date_fin = Carbon::createFromFormat('d/m/Y', $data['date_fin']);
            $data['duree'] = $date_fin->diffInMonths($date_debut);
        }
    
        $promotion = $this->promotionRepository->updatePromotion($id, $data);
    
        return $promotion;
    }
        public function updatePromotionReferentiels($id, $data)
    {
        $promotion = $this->promotionRepository->getPromotionById($id);

        if (!$promotion) {
            throw new \Exception('Promotion non trouvée');
        }

        $validator = Validator::make($data, [
            'referentiels' => 'required|array',
            'referentiels.*' => 'exists:referentiels,id',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $referentiels = [];
        foreach ($data['referentiels'] as $referentielId) {
            $referentiel = $this->referentielService->getReferentielById($referentielId);
            if ($referentiel && $referentiel->etat === 'actif') {
                $referentiels[] = $referentiel;
            }
        }

        $promotion->referentiels = $referentiels;
        $promotion->save();

        return $promotion;
    }
    public function getAllPromotions()
    {
        return $this->promotionRepository->getAllPromotions();
    }
    public function getCurrentPromotion()
    {
        
        $promotions = $this->promotionRepository->getAllPromotions();
        

        foreach ($promotions as $promotion) {
          
            // $date_debut = Carbon::createFromFormat('d/m/Y', $promotion->date_debut);
            // $date_fin = Carbon::createFromFormat('d/m/Y', $promotion->date_fin);

            if ($promotion['etat'] =='actif') {
                return $promotion;
            }
        }

        throw new \Exception('Aucune promotion en cours');
    }
    
    public function closePromotion($id)
    {
        $promotion = $this->promotionRepository->getPromotionById($id);

        if (!$promotion) {
            throw new \Exception('Promotion non trouvée');
        }

        $currentDate = Carbon::now();
        $date_fin = Carbon::createFromFormat('d/m/Y', $promotion->date_fin);

        if ($currentDate->gt($date_fin)) {
            $promotion->etat = 'cloturer';
            $this->promotionRepository->updatePromotion($id, $promotion->toArray());
            return $promotion;
        } else {
            throw new \Exception('Impossible de clôturer la promotion. La date de fin n\'est pas dépassée.');
        }
    }

}    
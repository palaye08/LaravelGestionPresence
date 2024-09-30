<?php

namespace App\Http\Controllers;

use App\Services\ReferentielService;
use Illuminate\Http\Request;

class ReferentielController extends Controller
{
    protected $serviceReferentiel;

    public function __construct(ReferentielService $serviceReferentiel)
    {
        $this->serviceReferentiel = $serviceReferentiel;
    }

    public function createReferentiel(Request $request) 
    {
        $data = $request->all();

        $referentielId = $this->serviceReferentiel->createReferentiel($data);

        return response()->json(['referentiel_id' => $referentielId], 201);
    }
    public function getActiveReferentiels()
    {
        $referentiels = $this->serviceReferentiel->getActiveReferentiels();
        return response()->json($referentiels);
    }
    public function getReferentielsByEtat(Request $request)
    {
        $etat = $request->query('etat');
    
        if (empty($etat)) {
            return response()->json(['error' => 'Le paramètre etat est requis'], 400);
        }
    
        try {
            $referentiels = $this->serviceReferentiel->getReferentielsByEtat($etat);
            return response()->json($referentiels);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue lors de la récupération des référentiels'], 500);
        }
    }
    public function getReferentielCompetences($id)
    {
        try {
            $competences = $this->serviceReferentiel->getReferentielCompetences($id);
            return response()->json($competences);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getReferentielModules($id)
    {
        try {
            $modules = $this->serviceReferentiel->getReferentielModules($id);
            return response()->json($modules);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function updateReferentiel(Request $request)
    {
        try {
            $id = $request->route('id'); // Récupère l'ID depuis la route
            $data = $request->all();
            
            // Log pour le débogage
            \Log::info('Données reçues pour la mise à jour', ['id' => $id, 'data' => $data]);

            $referentiel = $this->serviceReferentiel->updateReferentiel($id, $data);
            
            return response()->json($referentiel);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la mise à jour du référentiel', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }



    public function softDeleteReferentiel($id)
    {
        try {
            $referentiel = $this->serviceReferentiel->softDeleteReferentiel($id);
            return response()->json(['message' => 'Référentiel supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
   
    public function getDeletedReferentiels()
    {
        $referentiels = $this->serviceReferentiel->getDeletedReferentiels();
        return response()->json($referentiels);
    }

}

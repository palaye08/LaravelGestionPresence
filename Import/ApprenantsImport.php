<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Services\ApprenantService;

class ApprenantsImport implements ToModel, WithHeadingRow
{
    protected $apprenantService;
    protected $failedRows = [];

    public function __construct(ApprenantService $apprenantService)
    {
        $this->apprenantService = $apprenantService;
    }

    public function model(array $row)
    {
        try {
            $this->apprenantService->createApprenant([
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'email' => $row['email'],
                'telephone' => $row['telephone'],
                'adresse' => $row['adresse'],
                'sexe' => $row['sexe'],
                'date_naissance' => $row['date_naissance'],
                'referentiel_id' => $row['referentiel_id'],
                'tuteur_nom' => $row['tuteur_nom'],
                'tuteur_prenom' => $row['tuteur_prenom'],
                'tuteur_contact' => $row['tuteur_contact'],
                // Ajoutez d'autres champs selon vos besoins
            ]);
        } catch (\Exception $e) {
            $row['commentaire'] = $e->getMessage();
            $this->failedRows[] = $row;
        }
    }

    public function getFailedRows()
    {
        return $this->failedRows;
    }
}
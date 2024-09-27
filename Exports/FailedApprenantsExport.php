<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FailedApprenantsExport implements FromArray, WithHeadings
{
    protected $failedRows;

    public function __construct(array $failedRows)
    {
        $this->failedRows = $failedRows;
    }

    public function array(): array
    {
        return $this->failedRows;
    }

    public function headings(): array
    {
        // Assurez-vous que cela correspond aux en-têtes de votre fichier d'importation
        return [
            'Nom',
            'Prénom',
            'Email',
            'Téléphone',
            'Adresse',
            'Sexe',
            'Date de naissance',
            'Référentiel ID',
            'Nom du tuteur',
            'Prénom du tuteur',
            'Contact du tuteur',
            'Commentaire'
        ];
    }
}
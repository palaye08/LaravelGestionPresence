<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReferentielNotesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $notes;

    public function __construct($notes)
    {
        $this->notes = $notes;
    }

    public function collection()
    {
        return $this->notes->map(function ($note) {
            return [
                'Apprenant ID' => $note->apprenantId,
                'Nom' => $note->apprenant->nom,
                'Prénom' => $note->apprenant->prenom,
                'Module' => $note->module->nom,
                'Note' => $note->note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Apprenant ID',
            'Nom',
            'Prénom',
            'Module',
            'Note',
        ];
    }
}
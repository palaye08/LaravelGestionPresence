<?php

namespace App\Services;

use App\Repositories\ModuleRepository;
use App\Exports\ReferentielNotesExport;
use App\Exports\ApprenantNotesExport;
use Maatwebsite\Excel\Facades\Excel;

class ModuleService
{
    protected $moduleRepository;

    public function __construct(ModuleRepository $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
    }

    public function addNoteToGroup($moduleId, $notes)
{
    return $this->moduleRepository->addNoteToGroup($moduleId, $notes);
}
public function addNotesToApprenant($apprenantId, $notes)
{
    return $this->moduleRepository->addNotesToApprenant($apprenantId, $notes);
}

public function updateApprenantNotes($apprenantId, $notes)
{
    return $this->moduleRepository->updateApprenantNotes($apprenantId, $notes);
}
public function getNotesForReferentiel($referentielId)
{
    return $this->moduleRepository->getNotesForReferentiel($referentielId);
}
public function exportReferentielNotes($referentielId)
{
    $notes = $this->moduleRepository->getNotesForReferentiel($referentielId);
    return Excel::download(new ReferentielNotesExport($notes), 'referentiel_notes.xlsx');
}

public function exportApprenantNotes($apprenantId)
{
    $notes = $this->moduleRepository->getNotesForApprenant($apprenantId);
    return Excel::download(new ApprenantNotesExport($notes), 'apprenant_notes.xlsx');
}

public function calculateAverage($notes)
{
    if (count($notes) === 0) {
        return 0;
    }
    return $notes->sum('note') / count($notes);
}


}
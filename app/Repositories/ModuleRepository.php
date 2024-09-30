<?php

namespace App\Repositories;

use App\Models\Module;

class ModuleRepository
{
    protected $model;

    public function __construct(Module $model)
    {
        $this->model = $model;
    }
    public function addNoteToGroup($moduleId, $notes)
    {
        $module = $this->model->find($moduleId);
        if (!$module) {
            throw new \Exception("Module not found");
        }
    
        foreach ($notes as $note) {
            $module->create([
                'apprenantId' => $note['apprenantId'],
                'note' => $note['note']
            ]);
        }
    
        return $module;
    }
    public function addNotesToApprenant($apprenantId, $notes)
{
    foreach ($notes as $note) {
        $module = $this->model->find($note['moduleId']);
        if (!$module) {
            throw new \Exception("Module not found");
        }

        $module->create([
            'apprenantId' => $apprenantId,
            'note' => $note['note']
        ]);
    }

    return $this->model->where('apprenantId', $apprenantId)->get();
}
public function updateApprenantNotes($apprenantId, $notes)
{
    foreach ($notes as $note) {
        $moduleNote = $this->model->find($note['noteId']);
        if (!$moduleNote || $moduleNote->apprenantId != $apprenantId) {
            throw new \Exception("Note not found or does not belong to the apprenant");
        }

        $moduleNote->update(['note' => $note['note']]);
    }

    return $this->model->where('apprenantId', $apprenantId)->get();
}
public function getNotesForReferentiel($referentielId)
{
    return $this->model->whereHas('referentiel', function ($query) use ($referentielId) {
        $query->where('id', $referentielId);
    })->with('apprenant')->get();
}


public function getNotesForApprenant($apprenantId)
{
    return $this->model->where('apprenantId', $apprenantId)->get();
}
}
<?php

namespace App\Http\Controllers;

use App\Services\ModuleService;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    public function addNoteToGroup(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'notes' => 'required|array',
        'notes.*.apprenantId' => 'required|integer',
        'notes.*.note' => 'required|numeric'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    try {
        $result = $this->moduleService->addNoteToGroup($id, $request->notes);
        return response()->json(['message' => 'Notes added successfully', 'data' => $result]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function addNotesToApprenant(Request $request)
{
    $validator = Validator::make($request->all(), [
        'apprenantId' => 'required|integer',
        'notes' => 'required|array',
        'notes.*.moduleId' => 'required|integer',
        'notes.*.note' => 'required|numeric'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    try {
        $result = $this->moduleService->addNotesToApprenant($request->apprenantId, $request->notes);
        return response()->json(['message' => 'Notes added successfully', 'data' => $result]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function updateApprenantNotes(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'notes' => 'required|array',
        'notes.*.noteId' => 'required|integer',
        'notes.*.note' => 'required|numeric'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    try {
        $result = $this->moduleService->updateApprenantNotes($id, $request->notes);
        return response()->json(['message' => 'Notes updated successfully', 'data' => $result]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
public function getNotesForReferentiel($id)
{
    try {
        $result = $this->moduleService->getNotesForReferentiel($id);
        return response()->json(['data' => $result]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function exportReferentielNotes($id)
{
    try {
        return $this->moduleService->exportReferentielNotes($id);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function exportApprenantNotes($id)
{
    try {
        return $this->moduleService->exportApprenantNotes($id);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
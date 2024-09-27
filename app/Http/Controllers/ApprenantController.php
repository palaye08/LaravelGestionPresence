<?php

namespace App\Http\Controllers;

use App\Services\ApprenantService;
use Illuminate\Http\Request;

class ApprenantController extends Controller
{
    protected $apprenantService;

    public function __construct(ApprenantService $apprenantService)
    {
        $this->apprenantService = $apprenantService;
    }

    public function createApprenant(Request $request)
    {
        try {
            $data = $request->all();
            $apprenant = $this->apprenantService->createApprenant($data);
            return response()->json($apprenant);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
        public function importApprenants(Request $request)
        {
            $file = $request->file('file');
            $result = $this->apprenantService->importApprenants($file);
            return response()->json($result);
        }

       public function listApprenants(Request $request)
        {
            $filters = $request->only(['referentiel_id', 'statut']);
            $apprenants = $this->apprenantService->listApprenants($filters);
            return response()->json($apprenants);
        }

        public function show($id)
        {
            try {
                $result = $this->apprenantService->getApprenantWithReferentiel($id);
                return response()->json($result, 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 404);
            }
        }
}

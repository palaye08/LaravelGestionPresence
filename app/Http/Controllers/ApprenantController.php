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
}

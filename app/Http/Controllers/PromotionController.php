<?php

namespace App\Http\Controllers;

use App\Services\PromotionService;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function createPromotion(Request $request)
    {
        try {
            // dd($request);
            $data = $request->all();
            $promotion = $this->promotionService->createPromotion($data);
            return response()->json($promotion, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function updatePromotion(Request $request, $id)
    {
        try {
            $data = $request->all();
            $promotion = $this->promotionService->updatePromotion($id, $data);
            return response()->json($promotion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function updatePromotionReferentiels(Request $request, $id)
    {
        try {
            $data = $request->all();
            $promotion = $this->promotionService->updatePromotionReferentiels($id, $data);
            return response()->json($promotion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function getAllPromotions()
    {
        $promotions = $this->promotionService->getAllPromotions();
        return response()->json($promotions);
    }

    public function getCurrentPromotion()
    {
        try {
            $promotion = $this->promotionService->getCurrentPromotion();
            return response()->json($promotion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function getActiveReferentiels($id)
    {
        try {
            $referentiels = $this->promotionService->getActiveReferentiels($id);
            return response()->json($referentiels);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
        public function closePromotion($id)
    {
        try {
            $promotion = $this->promotionService->closePromotion($id);
            return response()->json($promotion);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



}

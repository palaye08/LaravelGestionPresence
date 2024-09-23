<?php
namespace App\Services;
 
interface PromotionServiceInterface {

    public function createPromotion($data);

    public function updatePromotion($id, $data);

    public function updatePromotionReferentiels($id, $data);

    public function getAllPromotions();

    public function getCurrentPromotion();

}
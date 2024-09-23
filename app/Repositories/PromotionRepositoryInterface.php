<?php
namespace App\Repositories;
 

interface PromotionRepositoryInterface {

    public function getAllPromotions();

    public function getPromotionById($id);

    public function createPromotion($data);

    public function updatePromotion($id, $data);

    public function deletePromotion($id);



}
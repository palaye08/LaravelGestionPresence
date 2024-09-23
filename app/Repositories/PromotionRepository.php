<?php

namespace App\Repositories;

use App\Models\Promotion;

class PromotionRepository implements PromotionRepositoryInterface
{
    protected $model;

    public function __construct(Promotion $model)
    {
        $this->model = $model;
    }

    public function getAllPromotions()
    {
        return $this->model->all();
    }

    public function getPromotionById($id)
    {
        return $this->model->find($id);
    }

    public function createPromotion($data)
    {
        return $this->model->create($data);
    }

    public function updatePromotion($id, $data)
    {
        $promotion = $this->model->find($id);
        $promotion->update($data);
        return $promotion;
    }

    public function deletePromotion($id)
    {
        return $this->model->destroy($id);
    }
    
}

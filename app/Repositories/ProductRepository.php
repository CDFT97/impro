<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function __construct(Product $entity)
    {
        parent::__construct($entity);
    }

    public function getLowMettersStocks()
    {
        return $this->model->where("stock_meters", "<=", 10)->get();
    }
}
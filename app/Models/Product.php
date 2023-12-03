<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "material",
        "stock_meters",
        // "stock_quantity",
        "unit_price_in_dollars",
        "description",
    ];

    // protected $with = ["orders"];

    public function orders(): Relation
    {
        return $this->belongsToMany(Order::class, "order_product");
    }
}

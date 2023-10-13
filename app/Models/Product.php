<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "material",
        "stock_meters",
        "stock_quantity",
        "unit_price_in_dollars",
        "description",
    ];
}

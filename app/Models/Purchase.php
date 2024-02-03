<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider_id',
        'product_id',
        'amount_usd', 
        'amount', 
        'date', 
        'description',
        'dolar_price',
        'quantity_meters'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}

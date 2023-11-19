<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "amount", "hash", 'status', 'vouche'];

    const STATUS = ["Pending", "Completed", "Canceled"];

    public function products(): Relation
    {
        return $this->belongsToMany(Product::class, "order_product")->withPivot('units', 'quantity');
    }
}

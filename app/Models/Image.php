<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'url',
        'extension'
    ];

    public function order(): Relation 
    {
        return $this->belongsTo(Order::class);
    }

}

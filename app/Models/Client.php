<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'address',
        'phone_number',
        'email',
        'company',
        'type',
        'discount',
        'description'
    ];

    const TYPE = [
        0 => "Normal",
        1 => "Publicista"
    ];

    // protected function getTypeAttribute()
    // {
    //     return Client::TYPE[$this->attributes['type']];
    // }
}
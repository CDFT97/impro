<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'rif',
        'phone_number',
        'address',
        'email',
        'description',
        'company',
        'description',
    ];
}

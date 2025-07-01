<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = [
        'name',
        'provider',
        'departure',
        'arrival',
        'departure_time',
        'arrival_time',
        'price',
        'seats',
        'image',
    ];
}

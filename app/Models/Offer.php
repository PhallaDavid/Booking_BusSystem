<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $casts = [
        'valid_till' => 'datetime',
    ];
}

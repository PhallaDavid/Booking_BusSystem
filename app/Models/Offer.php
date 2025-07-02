<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'title',
        'image',
        'code',
        'valid_till',
        'discount_percent',
        'start_date',
        'end_date',
        'type',
        'conditions',
    ];
    protected $casts = [
        'valid_till' => 'datetime',
        'discount_percent' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}

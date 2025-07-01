<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'booking_id',
        'ticket_number',
        'status',
        'issued_at',
        'delivery_method'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

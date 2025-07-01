<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BookingSeat;
use App\Models\Booking;

class BookingPassenger extends Model
{
    protected $fillable = [
        'booking_id',
        'name',
        'gender',
        'age',
        'email',
        'phone'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function seats()
    {
        return $this->hasMany(BookingSeat::class, 'passenger_id');
    }
}

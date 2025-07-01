<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BookingPassenger;
use App\Models\Booking;
use App\Models\Bus;

class BookingSeat extends Model
{
    protected $fillable = [
        'booking_id',
        'bus_id',
        'passenger_id',
        'seat_number'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
    public function passenger()
    {
        return $this->belongsTo(BookingPassenger::class, 'passenger_id');
    }
}

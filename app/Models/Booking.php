<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BookingPassenger;
use App\Models\BookingSeat;
use App\Models\Ticket;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'bus_id',
        'travel_date',
        'total_price',
        'discount_code',
        'discount_amount',
        'payment_method',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
    public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }
    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }
    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}

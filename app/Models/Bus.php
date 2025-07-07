<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'is_promotion',
        'promotion_start_date',
        'promotion_end_date',
        'promotion_discount',
        'promotion_price',
        'type',
    ];

    protected $casts = [
        'is_promotion' => 'boolean',
        'promotion_start_date' => 'date',
        'promotion_end_date' => 'date',
        'promotion_discount' => 'decimal:2',
        'promotion_price' => 'decimal:2',
    ];

    /**
     * Check if promotion is currently active
     */
    public function isPromotionActive()
    {
        if (!$this->is_promotion) {
            return false;
        }

        $now = Carbon::now();
        $startDate = Carbon::parse($this->promotion_start_date);
        $endDate = Carbon::parse($this->promotion_end_date);

        return $now->between($startDate, $endDate);
    }

    /**
     * Get the current price (original or promotion price)
     */
    public function getCurrentPrice()
    {
        if ($this->isPromotionActive()) {
            return $this->promotion_price ?? $this->price;
        }
        return $this->price;
    }

    /**
     * Calculate promotion price based on discount percentage
     */
    public function calculatePromotionPrice()
    {
        if ($this->promotion_discount && $this->promotion_discount > 0) {
            $discount = $this->price * ($this->promotion_discount / 100);
            return $this->price - $discount;
        }
        return $this->price;
    }

    /**
     * Scope to get buses with active promotions
     */
    public function scopeWithActivePromotions($query)
    {
        return $query->where('is_promotion', true)
            ->where('promotion_start_date', '<=', Carbon::now())
            ->where('promotion_end_date', '>=', Carbon::now());
    }

    /**
     * Get available seat numbers for this bus
     */
    public function getAvailableSeats()
    {
        $allSeats = range(1, $this->seats);
        $bookedSeats = \App\Models\BookingSeat::where('bus_id', $this->id)->pluck('seat_number')->toArray();
        $bookedSeats = array_map('intval', $bookedSeats);
        // Exclude blocked seats for today
        $today = now()->toDateString();
        $blockedSeats = $this->blockedSeats()
            ->where(function ($q) use ($today) {
                $q->where('start_date', '<=', $today)
                    ->where(function ($q2) use ($today) {
                        $q2->whereNull('end_date')->orWhere('end_date', '>=', $today);
                    });
            })
            ->pluck('seat_number')->toArray();
        $blockedSeats = array_map('intval', $blockedSeats);
        return array_values(array_diff($allSeats, $bookedSeats, $blockedSeats));
    }

    /**
     * Get the recurring schedules for the bus.
     */
    public function schedules()
    {
        return $this->hasMany(BusSchedule::class);
    }

    /**
     * Get the blocked seats for the bus.
     */
    public function blockedSeats()
    {
        return $this->hasMany(BlockedSeat::class);
    }
}

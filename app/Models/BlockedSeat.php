<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedSeat extends Model
{
    protected $fillable = [
        'bus_id',
        'seat_number',
        'reason',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}

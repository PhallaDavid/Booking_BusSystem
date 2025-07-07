<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusSchedule extends Model
{
    protected $fillable = [
        'bus_id',
        'recurrence_type',
        'days_of_week',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}

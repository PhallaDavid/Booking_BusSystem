<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function apiShow($id)
    {
        $ticket = Ticket::with(['booking.user', 'booking.bus'])->findOrFail($id);
        return response()->json(['data' => $ticket]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bus;
use App\Models\Booking;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userCount = User::count();
        $roleCount = Role::count();
        $permissionCount = Permission::count();

        // Get active promotions
        $activePromotions = Bus::withActivePromotions()->get();
        $activePromotionsCount = $activePromotions->count();

        // Profit by month
        $year = $request->input('year', now()->year);
        $month = $request->input('month');
        $query = Booking::query();
        if ($year) {
            $query->whereYear('travel_date', $year);
        }
        if ($month) {
            $query->whereMonth('travel_date', $month);
        }
        $bookings = $query->get();

        // Group by month
        $monthlyProfit = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyProfit[$m] = 0;
        }
        foreach ($bookings as $booking) {
            $m = Carbon::parse($booking->travel_date)->month;
            $monthlyProfit[$m] += ($booking->total_price - $booking->discount_amount);
        }
        // Format for chart
        $profitLabels = array_map(function ($m) {
            return date('M', mktime(0, 0, 0, $m, 1));
        }, range(1, 12));
        $profitData = array_values($monthlyProfit);
        $profitLabels = isset($profitLabels) && is_array($profitLabels) ? $profitLabels : [];
        $profitData = isset($profitData) && is_array($profitData) ? $profitData : [];

        // Gross and Net Totals (all bookings, like bookings page)
        $grossTotal = Booking::sum('total_price');
        $netTotal = Booking::sum(DB::raw('total_price - discount_amount'));

        return view('dashboard', compact(
            'userCount',
            'roleCount',
            'permissionCount',
            'activePromotions',
            'activePromotionsCount',
            'profitLabels',
            'profitData',
            'year',
            'month',
            'grossTotal',
            'netTotal'
        ));
    }
}

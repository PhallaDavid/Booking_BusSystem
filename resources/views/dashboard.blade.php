@extends('layouts.app')

@section('content')
@php
$systemCounts = [
$userCount,
$roleCount,
$permissionCount,
$activePromotionsCount ?? 0
];
@endphp

<div class="container mx-auto py-8">

    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="mb-6">
        <div class="bg-white p-4 rounded shadow flex flex-col md:flex-row gap-4 md:gap-12 items-center">
            <div class="text-lg font-semibold text-gray-700">Gross Total: <span class="text-blue-700 font-bold">${{ number_format($grossTotal, 2) }}</span></div>
            <div class="text-lg font-semibold text-gray-700">Net Total (after discount): <span class="text-green-700 font-bold">${{ number_format($netTotal, 2) }}</span></div>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="" class="mb-8 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Year</label>
            <select name="year" class="border rounded px-3 py-2">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                <option value="{{ $y }}" @if($year==$y) selected @endif>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Month</label>
            <select name="month" class="border rounded px-3 py-2">
                <option value="">All</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @if($month==$m) selected @endif>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
            </select>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
        </div>
    </form>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $userCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Roles</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $roleCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Permissions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $permissionCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Promotions</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activePromotionsCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Promotions Section -->
    @if(isset($activePromotions) && $activePromotions->count() > 0)
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Active Promotions</h2>
            <p class="text-sm text-gray-600 mt-1">Currently running promotions on buses</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promotion Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valid Until</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activePromotions as $bus)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $bus->name }}</div>
                            <div class="text-sm text-gray-500">{{ $bus->provider }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $bus->departure }} → {{ $bus->arrival }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="line-through text-gray-500">${{ number_format($bus->price, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-red-600 font-bold">${{ number_format($bus->getCurrentPrice(), 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $bus->promotion_discount }}% OFF
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $bus->promotion_end_date->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Profit Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Monthly Profit ({{ $year }})</h2>
        <canvas id="profitChart" width="400" height="200"></canvas>
    </div>

    <!-- Chart Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">System Statistics</h2>
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // System Statistics Chart
        const ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Users", "Roles", "Permissions", "Active Promotions"],
                datasets: [{
                    label: 'System Counts',
                    data: @json($systemCounts),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });

        // Profit Chart
        const profitCtx = document.getElementById('profitChart').getContext('2d');
        new Chart(profitCtx, {
            type: 'line',
            data: {
                labels: @json($profitLabels ?? []),
                datasets: [{
                    label: 'Profit ($)',
                    data: @json($profitData ?? []),
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    });
</script>
@endpush
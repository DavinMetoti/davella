@extends('pages.layout')

@section('main')
<div class="">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Sales Report</h1>
        <p class="text-gray-600 mt-1">Performance analysis of sales personnel</p>
    </div>

    <!-- Overall Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700">Total Sales</p>
                    <p class="text-3xl font-bold text-blue-900">{{ number_format($totalSales) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Active personnel</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700">Total Reservations</p>
                    <p class="text-3xl font-bold text-green-900">{{ number_format($totalAllReservations) }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ number_format($totalConfirmedReservations) }} confirmed</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-calendar-check text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-700">Conversion Rate</p>
                    <p class="text-3xl font-bold text-purple-900">{{ $totalAllReservations > 0 ? round(($totalConfirmedReservations / $totalAllReservations) * 100, 1) : 0 }}%</p>
                    <p class="text-xs text-purple-600 mt-1">Overall performance</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 p-6 rounded-lg border border-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-700">Total Revenue</p>
                    <p class="text-3xl font-bold text-orange-900">Rp {{ number_format($totalRevenueAll, 0, ',', '.') }}</p>
                    <p class="text-xs text-orange-600 mt-1">From confirmed</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <i class="fas fa-dollar-sign text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Sales Performers</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Person</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confirmed</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topPerformers as $index => $sales)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-gray-100 text-gray-800' : ($index === 2 ? 'bg-orange-100 text-orange-800' : 'bg-gray-50 text-gray-600')) }} font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sales['name'] }}</div>
                                <div class="text-sm text-gray-500">{{ $sales['email'] }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ $sales['confirmed_reservations'] }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sales['conversion_rate'] >= 50 ? 'bg-green-100 text-green-800' : ($sales['conversion_rate'] >= 25 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $sales['conversion_rate'] }}%
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp {{ number_format($sales['total_revenue'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No sales performance data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Sales Performance Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Detailed Sales Performance</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Person</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Confirmed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancelled</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($salesPerformance as $sales)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-700 font-medium text-xs">{{ substr($sales['name'], 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $sales['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $sales['email'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sales['total_reservations'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ $sales['confirmed_reservations'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $sales['pending_reservations'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $sales['cancelled_reservations'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $sales['conversion_rate'] }}%"></div>
                                    </div>
                                    <span class="text-sm">{{ $sales['conversion_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($sales['total_revenue'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($sales['last_reservation'])
                                    {{ $sales['last_reservation']->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">No sales performance data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Performance Chart -->
    <div class="mt-8 bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Performance Trend</h3>
        <div class="text-sm text-gray-600 mb-4">
            Track sales performance over the last 6 months
        </div>
        <div class="overflow-x-auto">
            <div class="min-w-full">
                @if($salesPerformance->count() > 0)
                    <div class="space-y-6">
                        @foreach($salesPerformance->take(5) as $sales)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $sales['name'] }}</span>
                                    <span class="text-sm text-gray-500">{{ $sales['confirmed_reservations'] }} total confirmed</span>
                                </div>
                                <div class="flex items-end space-x-2 h-12">
                                    @php
                                        $maxConfirmed = collect($sales['monthly_data'])->pluck('confirmed')->max() ?? 0;
                                    @endphp
                                    @foreach($sales['monthly_data'] as $monthData)
                                        <div class="flex-1 flex flex-col items-center">
                                            <div class="w-full bg-blue-400 rounded-t mb-1"
                                                 style="height: {{ $maxConfirmed > 0 ? max(($monthData['confirmed'] / $maxConfirmed) * 40, 4) : 4 }}px"
                                                 title="{{ $monthData['month'] }}: {{ $monthData['confirmed'] }} confirmed">
                                            </div>
                                            <span class="text-xs text-gray-600 text-center">{{ $monthData['month'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No monthly performance data available</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
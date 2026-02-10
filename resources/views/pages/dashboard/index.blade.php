@extends('pages.layout')

@section('main')
<div class="min-h-screen">
    <!-- Header -->
    <div class="">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-0 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="mt-1 text-gray-600">Welcome back! Here's what's happening with your business today.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Last updated</p>
                        <p class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-0 py-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Clusters -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Clusters</p>
                        <p class="text-3xl font-bold">{{ number_format($totalClusters) }}</p>
                        <p class="text-blue-200 text-xs mt-1">{{ $activeClusters }} active</p>
                    </div>
                    <div class="p-3 bg-blue-400 bg-opacity-30 rounded-full">
                        <i class="fas fa-map-marker-alt text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Units -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Units</p>
                        <p class="text-3xl font-bold">{{ number_format($totalUnits) }}</p>
                        <p class="text-green-200 text-xs mt-1">{{ $activeUnits }} available</p>
                    </div>
                    <div class="p-3 bg-green-400 bg-opacity-30 rounded-full">
                        <i class="fas fa-home text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Reservations -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Reservations</p>
                        <p class="text-3xl font-bold">{{ number_format($totalReservations) }}</p>
                        <p class="text-purple-200 text-xs mt-1">{{ $confirmedReservations }} confirmed</p>
                    </div>
                    <div class="p-3 bg-purple-400 bg-opacity-30 rounded-full">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Total Revenue</p>
                        <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        <p class="text-yellow-200 text-xs mt-1">This month: Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-yellow-400 bg-opacity-30 rounded-full">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend (Last 6 Months)</h3>
                <div class="h-64 flex items-end justify-between space-x-2">
                    @foreach($monthlyRevenue as $data)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t mb-2"
                                 style="height: {{ $data['revenue'] > 0 ? max(($data['revenue'] / max(collect($monthlyRevenue)->pluck('revenue')->toArray())) * 200, 20) : 20 }}px">
                            </div>
                            <p class="text-xs text-gray-600 text-center">{{ $data['month'] }}</p>
                            <p class="text-xs font-medium text-gray-900">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Unit Status Distribution -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Unit Status Distribution</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">Available</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-900">{{ $unitStatusStats['available'] }}</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalUnits > 0 ? ($unitStatusStats['available'] / $totalUnits) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">Reserved</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-900">{{ $unitStatusStats['reserved'] }}</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalUnits > 0 ? ($unitStatusStats['reserved'] / $totalUnits) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-700">Booked</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-gray-900">{{ $unitStatusStats['booked'] }}</span>
                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $totalUnits > 0 ? ($unitStatusStats['booked'] / $totalUnits) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!Auth::check() || !Auth::user()->hasRole('sales'))
        <!-- Recent Activities and Top Performers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Recent Reservations -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Reservations</h3>
                <div class="space-y-4">
                    @forelse($recentReservations as $reservation)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-600"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $reservation->customer_name }}</p>
                                <p class="text-xs text-gray-600">{{ $reservation->unit->name ?? 'N/A' }} - {{ $reservation->unit->cluster->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $reservation->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                {!! $reservation->status_badge !!}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No recent reservations</p>
                    @endforelse
                </div>
            </div>
            @endif

            <!-- Top Sales Performers -->
            {{-- Debug: Auth check: {{ Auth::check() ? 'Logged in as ' . Auth::user()->name : 'Not logged in' }} --}}
            {{-- Debug: Has sales role: {{ Auth::check() && Auth::user()->hasRole('sales') ? 'YES' : 'NO' }} --}}
            @if(!Auth::check() || !Auth::user()->hasRole('sales'))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Sales Performers</h3>
                <div class="space-y-4">
                    @forelse($topSalesUsers as $index => $sales)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $sales->name }}</p>
                                <p class="text-xs text-gray-600">{{ $sales->reservations_count }} confirmed reservations</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-trophy text-yellow-500"></i>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No sales data available</p>
                    @endforelse
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <a href="{{ route('reservations.create') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <i class="fas fa-plus-circle text-blue-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-blue-900">New Reservation</span>
                </a>
                <a href="{{ route('units.create') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <i class="fas fa-home text-green-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-green-900">Add Unit</span>
                </a>
                <a href="{{ route('clusters.create') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <i class="fas fa-map-marker-alt text-purple-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-purple-900">Add Cluster</span>
                </a>
                <a href="{{ route('sales.report') }}" class="flex flex-col items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                    <i class="fas fa-chart-bar text-red-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-red-900">Sales Report</span>
                </a>
                <a href="{{ route('reservations.index') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                    <i class="fas fa-list text-orange-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-orange-900">View All</span>
                </a>
            </div>
        </div>

        <!-- Active Clusters and Units -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Active Clusters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Active Clusters</h3>
                    <a href="{{ route('clusters.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                </div>
                @if($activeClustersList->count() > 0)
                    <div class="space-y-4">
                        @foreach($activeClustersList as $cluster)
                            <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex-shrink-0">
                                    @if($cluster->site_plan_path)
                                        <img src="{{ Storage::url($cluster->site_plan_path) }}" alt="Site Plan" class="w-12 h-12 object-cover rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-map text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $cluster->name }}</p>
                                    <p class="text-xs text-gray-600">{{ Str::limit($cluster->address, 40) }}</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                        <span class="text-xs text-gray-500">{{ $cluster->units->count() }} units</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No active clusters</p>
                @endif
            </div>

            <!-- Active Units -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Available Units</h3>
                    <a href="{{ route('units.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
                </div>
                @if($activeUnitsList->count() > 0)
                    <div class="space-y-3">
                        @foreach($activeUnitsList as $unit)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $unit->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $unit->cluster->name ?? 'N/A' }} - Block {{ $unit->block }}/{{ $unit->number }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-600">{{ $unit->house_type }}</span>
                                    {!! $unit->status_badge !!}
                                    <a href="{{ route('reservations.create', ['unit_id' => $unit->id]) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-md transition-colors">
                                        <i class="fas fa-plus mr-1"></i>Reserve
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No available units</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@extends('pages.layout')

@section('main')
<h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard</h1>
<p class="text-gray-600 mb-8">Welcome to your application dashboard. Here's an overview of your data.</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Total Clusters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-map-marker-alt text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Clusters</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalClusters }}</p>
            </div>
        </div>
    </div>

    <!-- Total Units Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-home text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Units</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalUnits }}</p>
            </div>
        </div>
    </div>

    <!-- Active Clusters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Clusters</p>
                <p class="text-2xl font-bold text-gray-900">{{ $activeClusters }}</p>
            </div>
        </div>
    </div>

    <!-- Active Units Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                <i class="fas fa-building text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Units</p>
                <p class="text-2xl font-bold text-gray-900">{{ $activeUnits }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Active Clusters -->
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Active Clusters</h2>
    @if($activeClustersList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($activeClustersList as $cluster)
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @if($cluster->site_plan_path)
                                <img src="{{ Storage::url($cluster->site_plan_path) }}" alt="Site Plan" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-medium text-gray-900 truncate">{{ $cluster->name }}</h3>
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($cluster->address, 50) }}</p>
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                                @if($cluster->coordinates)
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-map-marker-alt"></i> Mapped
                                    </span>
                                @endif
                            </div>
                            <div class="mt-2 grid grid-cols-3 gap-1 text-xs">
                                <div class="text-center">
                                    <span class="block font-medium text-green-600">{{ $cluster->units->where('status', 'available')->count() }}</span>
                                    <span class="text-gray-500">Available</span>
                                </div>
                                <div class="text-center">
                                    <span class="block font-medium text-yellow-600">{{ $cluster->units->where('status', 'reserved')->count() }}</span>
                                    <span class="text-gray-500">Reserved</span>
                                </div>
                                <div class="text-center">
                                    <span class="block font-medium text-red-600">{{ $cluster->units->where('status', 'booked')->count() }}</span>
                                    <span class="text-gray-500">Booked</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No active clusters to display.</p>
    @endif
</div>

<!-- Active Units -->
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Active Units</h2>
    @if($activeUnitsList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($activeUnitsList as $unit)
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $unit->name }}</p>
                            <p class="text-sm text-gray-600">{{ $unit->cluster->name ?? 'N/A' }} - Block {{ $unit->block }}/{{ $unit->number }}</p>
                        </div>
                        <div class="text-right ml-4">
                            <p class="text-sm text-gray-600">{{ $unit->house_type }}</p>
                            {!! $unit->status_badge !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No active units to display.</p>
    @endif
</div>
@endsection
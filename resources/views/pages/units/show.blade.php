@extends('pages.layout')

@section('main')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Unit {{ $unit->block }}/{{ $unit->number }}</h1>
            <p class="text-gray-600">{{ $unit->cluster->name ?? 'N/A' }} - {{ $unit->house_type }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('units.edit', $unit) }}"
               class="px-4 py-2 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('units.index') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Unit Details -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Unit Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Unit Name</label>
                    <p class="text-gray-900 font-semibold">{{ $unit->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Unit Code</label>
                    <p class="text-gray-900">{{ $unit->block }}/{{ $unit->number }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">House Type</label>
                    <p class="text-gray-900">{{ $unit->house_type }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Land Area</label>
                    <p class="text-gray-900">{{ $unit->formatted_land_area }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Building Area</label>
                    <p class="text-gray-900">{{ $unit->formatted_building_area }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Price</label>
                    <p class="text-gray-900">
                        @if($unit->price)
                            Rp {{ number_format($unit->price, 0, ',', '.') }}
                        @else
                            Price not set
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Construction Progress</label>
                    <div class="mt-2">
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Progress</span>
                            <span class="font-semibold">{{ $unit->progress_percentage }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-[#2FA769] h-3 rounded-full transition-all duration-300" style="width: {{ $unit->progress }}%"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    {!! $unit->status_badge !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Information -->
    <div class="space-y-6">
        <!-- Cluster Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Cluster Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Cluster Name</label>
                    <p class="text-gray-900">{{ $unit->cluster->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Address</label>
                    <p class="text-gray-900">{{ $unit->cluster->address ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Developer</label>
                    <p class="text-gray-900">{{ $unit->cluster->developer->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>


</div>
<!-- Site Plan -->
@if($unit->cluster && $unit->cluster->site_plan_path)
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Site Plan Location</h2>
    <div class="mb-4">
        <h3 class="text-sm font-medium text-gray-700 mb-2">Legend:</h3>
        <div class="flex items-center gap-4">
            <div class="flex items-center mr-4">
                <div class="w-4 h-4 rounded-full mr-2" style="background-color: #10B981; border: 2px solid #374151;"></div>
                <span class="text-sm text-gray-600">Available</span>
            </div>
            <div class="flex items-center mr-4">
                <div class="w-4 h-4 rounded-full mr-2" style="background-color: #F59E0B; border: 2px solid #374151;"></div>
                <span class="text-sm text-gray-600">Reserved</span>
            </div>
            <div class="flex items-center mr-4">
                <div class="w-4 h-4 rounded-full mr-2" style="background-color: #EF4444; border: 2px solid #374151;"></div>
                <span class="text-sm text-gray-600">Booked</span>
            </div>
        </div>
    </div>
    <div class="relative inline-block max-w-full">
        <img id="site-plan-image" src="{{ Storage::url($unit->cluster->site_plan_path) }}" alt="Site Plan" class="w-full h-auto block border border-gray-300 rounded-lg">
        @if($unit->coordinates)
            <div id="unit-point" class="absolute w-4 h-4 rounded-full transform -translate-x-1/2 -translate-y-1/2 pointer-events-none" style="top: 0; left: 0; background-color: {{ $unit->status === 'available' ? '#10B981' : ($unit->status === 'reserved' ? '#F59E0B' : '#EF4444') }}; border: 2px solid #374151;"></div>
        @endif
    </div>
    <p class="text-sm text-gray-500 mt-2">
        @if($unit->coordinates)
            Unit location: {{ explode(',', $unit->coordinates)[0] * 100 }}%, {{ explode(',', $unit->coordinates)[1] * 100 }}%
        @else
            No location set
        @endif
    </p>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sitePlanImage = document.getElementById('site-plan-image');
    const unitPoint = document.getElementById('unit-point');

    function positionPoint() {
        if ('{{ $unit->coordinates }}') {
            const [xPercent, yPercent] = '{{ $unit->coordinates }}'.split(',');
            unitPoint.style.left = (parseFloat(xPercent) * 100) + '%';
            unitPoint.style.top = (parseFloat(yPercent) * 100) + '%';
        }
    }

    if (sitePlanImage && unitPoint && '{{ $unit->coordinates }}') {
        if (sitePlanImage.complete) {
            positionPoint();
        } else {
            sitePlanImage.onload = positionPoint;
        }
    }
});
</script>

@endsection
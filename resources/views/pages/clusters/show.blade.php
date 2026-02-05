@extends('pages.layout')

@section('main')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $cluster->name }}</h1>
            <p class="text-gray-600">{{ $cluster->address }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('clusters.edit', $cluster) }}"
               class="px-4 py-2 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('clusters.index') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Site Plan -->
        @if($cluster->site_plan_path)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Site Plan</h2>
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Legend:</h3>
                <div class="flex items-center space-x-6">
                    <div class="flex items-center mr-2">
                        <div class="w-4 h-4 rounded-full mr-2" style="background-color: #10B981; border: 2px solid #374151;"></div>
                        <span class="text-sm text-gray-600">Available</span>
                    </div>
                    <div class="flex items-center mr-2">
                        <div class="w-4 h-4 rounded-full mr-2" style="background-color: #F59E0B; border: 2px solid #374151;"></div>
                        <span class="text-sm text-gray-600">Reserved</span>
                    </div>
                    <div class="flex items-center mr-2">
                        <div class="w-4 h-4 rounded-full mr-2" style="background-color: #EF4444; border: 2px solid #374151;"></div>
                        <span class="text-sm text-gray-600">Booked</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <div class="relative">
                    <!-- Zoom Controls -->
                    <div class="absolute top-2 right-2 z-10 flex flex-col space-y-1">
                        <button id="zoom-in" class="btn-primary px-2">
                            <i class="fas fa-plus text-white"></i>
                        </button>
                        <button id="zoom-out" class="btn-primary px-2">
                            <i class="fas fa-minus text-white"></i>
                        </button>
                        <button id="zoom-reset" class="btn-primary px-2">
                            Reset
                        </button>
                    </div>
                    <div class="overflow-hidden">
                        <div class="relative inline-block rounded-lg border border-gray-300" id="site-plan-container" style="transform-origin: top left; transition: transform 0.3s ease;">
                            <div class="relative" id="site-plan-wrapper">
                                <img id="cluster-site-plan" src="{{ Storage::url($cluster->site_plan_path) }}" alt="Site Plan" class="block">
                                @foreach($cluster->units as $unit)
                                    @if($unit->coordinates)
                                        <div class="absolute w-4 h-4 rounded-full transform -translate-x-1/2 -translate-y-1/2 pointer-events-none" 
                                             style="top: 0; left: 0; background-color: {{ $unit->status === 'available' ? '#10B981' : ($unit->status === 'reserved' ? '#F59E0B' : '#EF4444') }}; border: 2px solid #374151;" 
                                             data-coordinates="{{ $unit->coordinates }}" 
                                             data-unit="{{ $unit->block }}/{{ $unit->number }}"
                                             title="Unit {{ $unit->block }}/{{ $unit->number }} - {{ ucfirst($unit->status) }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Description -->
        @if($cluster->description)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Description</h2>
            <p class="text-gray-700 leading-relaxed">{{ $cluster->description }}</p>
        </div>
        @endif

        <!-- Facilities -->
        @php
            $facilities = is_string($cluster->facilities) ? json_decode($cluster->facilities, true) : $cluster->facilities;
            $facilities = is_array($facilities) ? $facilities : [];
        @endphp
        @if($facilities && count($facilities) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Facilities</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($facilities as $facility)
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-check-circle text-[#2FA769]"></i>
                        <span class="text-gray-700">{{ $facility }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar Information -->
    <div class="space-y-6">
        <!-- Basic Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Developer</label>
                    <p class="text-gray-900">{{ $cluster->developer ? $cluster->developer->name : 'Not specified' }}</p>
                </div>

                @if($cluster->year_built)
                <div>
                    <label class="text-sm font-medium text-gray-500">Year Built</label>
                    <p class="text-gray-900">{{ $cluster->year_built }}</p>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-500">Area Size</label>
                    <p class="text-gray-900">{{ $cluster->area_size ?? 'Not specified' }} M²</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <p class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cluster->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $cluster->is_active ? 'Active' : 'Inactive' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Property Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Property Details</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Units</label>
                    <p class="text-gray-900">{{ number_format($cluster->total_units) }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Available Units</label>
                    <p class="text-gray-900">{{ number_format($cluster->available_units) }}</p>
                </div>

                @if($cluster->formatted_price_range !== 'Price not set')
                <div>
                    <label class="text-sm font-medium text-gray-500">Price Range</label>
                    <p class="text-gray-900 font-semibold text-[#2FA769]">{{ $cluster->formatted_price_range }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Location -->
        @if($cluster->coordinates)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Location</h2>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Coordinates</label>
                    <p class="text-gray-900 font-mono text-sm">{{ $cluster->coordinates }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Address</label>
                    <p class="text-gray-900">{{ $cluster->address }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Map</label>
                    <div id="cluster-map" class="w-full h-64 rounded-lg border border-gray-300 mt-2"></div>
                </div>

                <div class="pt-2">
                    <a href="https://www.google.com/maps?q={{ $cluster->latitude }},{{ $cluster->longitude }}"
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#2FA769] hover:bg-[#256f4a] transition duration-200">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        View on Google Maps
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>


</div>
<div class="space-y-6 pt-6">
    <!-- Units List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Units in this Cluster</h2>
            <a href="{{ route('units.create') }}?cluster_id={{ $cluster->id }}"
                class="inline-flex items-center px-4 py-2 bg-[#2FA769] text-white text-sm font-medium rounded-lg hover:bg-[#256f4a] transition duration-200">
                <i class="fas fa-plus mr-2"></i>Add Unit
            </a>
        </div>

        @if($cluster->units && $cluster->units->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">House Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Land Area</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coordinates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cluster->units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $unit->block }}/{{ $unit->number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $unit->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $unit->house_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($unit->land_area, 2) }} m²
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-[#2FA769] h-2 rounded-full" style="width: {{ $unit->progress }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $unit->progress }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($unit->status === 'available')
                                    <span class="px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full">Available</span>
                                @elseif($unit->status === 'reserved')
                                    <span class="px-2 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded-full">Reserved</span>
                                @elseif($unit->status === 'booked')
                                    <span class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full">Booked</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(empty($unit->coordinates))
                                    <span class="text-sm text-gray-500">Not set</span>
                                @else
                                    <span class="text-sm font-mono text-gray-900">{{ $unit->coordinates }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('units.show', $unit) }}"
                                        class="text-[#2FA769] hover:text-[#256f4a] transition duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('units.edit', $unit) }}"
                                        class="text-blue-600 hover:text-blue-800 transition duration-200">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-between items-center text-sm text-gray-500">
                <span>Showing {{ $cluster->units->count() }} units</span>
                <a href="{{ route('units.index') }}?cluster={{ $cluster->id }}"
                    class="text-[#2FA769] hover:text-[#256f4a] font-medium transition duration-200">
                    View all units →
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-home text-gray-300 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No units yet</h3>
                <p class="text-gray-500 mb-6">Get started by adding the first unit to this cluster.</p>
                <a href="{{ route('units.create') }}?cluster_id={{ $cluster->id }}"
                    class="inline-flex items-center px-4 py-2 bg-[#2FA769] text-white font-medium rounded-lg hover:bg-[#256f4a] transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Add First Unit
                </a>
            </div>
        @endif
    </div>
</div>

<script>
@if($cluster->coordinates)
document.addEventListener('DOMContentLoaded', function() {
    initMap('cluster-map', {{ $cluster->latitude }}, {{ $cluster->longitude }});
});
@endif

@if($cluster->site_plan_path)
document.addEventListener('DOMContentLoaded', function() {
    const sitePlanImage = document.getElementById('cluster-site-plan');
    const unitPoints = document.querySelectorAll('[data-coordinates]');
    const zoomInBtn = document.getElementById('zoom-in');
    const zoomOutBtn = document.getElementById('zoom-out');
    const zoomResetBtn = document.getElementById('zoom-reset');
    const container = document.getElementById('site-plan-container');

    let currentScale = 1;
    let panX = 0;
    let panY = 0;
    const minScale = 0.5;
    const maxScale = 3;
    const scaleStep = 0.25;

    let isPanning = false;
    let startX, startY;

    function updateTransform() {
        container.style.transform = `scale(${currentScale}) translate(${panX}px, ${panY}px)`;
        unitPoints.forEach(point => {
            point.style.transform = `translate(-50%, -50%) scale(${1 / currentScale})`;
        });
    }

    function positionPoints() {
        unitPoints.forEach(point => {
            const coordinates = point.getAttribute('data-coordinates');
            if (coordinates) {
                const [xPercent, yPercent] = coordinates.split(',');
                point.style.left = (parseFloat(xPercent) * 100) + '%';
                point.style.top = (parseFloat(yPercent) * 100) + '%';
            }
        });
    }

    // Pan functionality
    container.addEventListener('mousedown', function(e) {
        if (currentScale > 1) { // Only allow panning when zoomed
            isPanning = true;
            startX = e.clientX - panX;
            startY = e.clientY - panY;
            container.style.cursor = 'grabbing';
        }
    });

    document.addEventListener('mousemove', function(e) {
        if (isPanning) {
            panX = e.clientX - startX;
            panY = e.clientY - startY;
            
            // Limit pan to keep image within bounds
            const maxPanX = (currentScale - 1) * container.offsetWidth / 2;
            const maxPanY = (currentScale - 1) * container.offsetHeight / 2;
            panX = Math.max(-maxPanX, Math.min(maxPanX, panX));
            panY = Math.max(-maxPanY, Math.min(maxPanY, panY));
            
            updateTransform();
        }
    });

    document.addEventListener('mouseup', function() {
        isPanning = false;
        container.style.cursor = currentScale > 1 ? 'grab' : 'default';
    });

    // Zoom controls
    zoomInBtn.addEventListener('click', function() {
        if (currentScale < maxScale) {
            currentScale += scaleStep;
            updateTransform();
            container.style.cursor = currentScale > 1 ? 'grab' : 'default';
        }
    });

    zoomOutBtn.addEventListener('click', function() {
        if (currentScale > minScale) {
            currentScale -= scaleStep;
            // Reset pan when zooming out to prevent image going out of bounds
            if (currentScale <= 1) {
                panX = 0;
                panY = 0;
            }
            updateTransform();
            container.style.cursor = currentScale > 1 ? 'grab' : 'default';
        }
    });

    zoomResetBtn.addEventListener('click', function() {
        currentScale = 1;
        panX = 0;
        panY = 0;
        updateTransform();
        container.style.cursor = 'default';
    });

    // Mouse wheel zoom
    container.addEventListener('wheel', function(e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            // Zoom in
            if (currentScale < maxScale) {
                currentScale += scaleStep;
                updateTransform();
                container.style.cursor = currentScale > 1 ? 'grab' : 'default';
            }
        } else {
            // Zoom out
            if (currentScale > minScale) {
                currentScale -= scaleStep;
                if (currentScale <= 1) {
                    panX = 0;
                    panY = 0;
                }
                updateTransform();
                container.style.cursor = currentScale > 1 ? 'grab' : 'default';
            }
        }
    });

    if (sitePlanImage) {
        if (sitePlanImage.complete) {
            positionPoints();
        } else {
            sitePlanImage.onload = positionPoints;
        }
    }
});
@endif
</script>
@endsection
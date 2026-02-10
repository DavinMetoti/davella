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
                <div class="w-full max-w-4xl">
                    <!-- Controls -->
                    <div class="flex flex-wrap justify-center gap-2 mb-4 p-3 bg-gray-50 rounded-lg">
                        <button class="zoom-in px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200 flex items-center gap-2" onclick="zoomIn()">
                            <i class="fas fa-search-plus"></i>
                            Zoom In
                        </button>
                        <button class="zoom-out px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 flex items-center gap-2" onclick="zoomOut()">
                            <i class="fas fa-search-minus"></i>
                            Zoom Out
                        </button>
                        <button class="reset px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 flex items-center gap-2" onclick="resetZoom()">
                            <i class="fas fa-undo"></i>
                            Reset
                        </button>
                        <div class="px-4 py-2 bg-white rounded-lg border border-gray-300 flex items-center gap-2">
                            <i class="fas fa-info-circle text-gray-500"></i>
                            <span class="font-medium text-gray-700">Zoom:</span>
                            <span id="zoomLevel" class="font-bold text-gray-900">100%</span>
                        </div>
                    </div>

                <div class="w-full flex justify-center">
                    <div id="viewer" class="relative bg-gray-100 rounded-lg overflow-hidden border border-gray-300 cursor-grab active:cursor-grabbing" style="min-height: 300px; max-height: 80vh;">
                        <!-- Container for image and points that gets transformed -->
                        <div id="imageContainer" style="position: absolute;">
                            <img id="zoomImage" src="{{ Storage::url($cluster->site_plan_path) }}" alt="Site Plan" class="block select-none pointer-events-none" style="max-width: none;" />
                            @foreach($cluster->units as $unit)
                                @if($unit->coordinates)
                                    <div class="unit-point absolute w-4 h-4 rounded-full border-2 border-gray-800 transform -translate-x-1/2 -translate-y-1/2 transition-all duration-200 hover:scale-125 cursor-pointer"
                                         data-coordinates="{{ $unit->coordinates }}"
                                         data-unit-id="{{ $unit->id }}"
                                         data-unit-name="{{ $unit->name }}"
                                         data-unit-code="{{ $unit->block }}/{{ $unit->number }}"
                                         data-house-type="{{ $unit->house_type }}"
                                         data-land-area="{{ number_format($unit->land_area, 2) }}"
                                         data-building-area="{{ number_format($unit->building_area, 2) }}"
                                         data-progress="{{ $unit->progress }}"
                                         data-status="{{ $unit->status }}"
                                         data-price="{{ $unit->price ? 'Rp ' . number_format($unit->price, 0, ',', '.') : 'Price not set' }}"
                                         style="background-color: {{ $unit->status === 'available' ? '#10B981' : ($unit->status === 'reserved' ? '#F59E0B' : '#EF4444') }};"
                                         onclick="showUnitDetail(this)">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Unit Detail Popover -->
                <div id="unitPopover" class="fixed z-50 hidden bg-white rounded-lg shadow-xl border border-gray-200 p-6 max-w-xl min-w-[400px]">
                    <div class="flex justify-between items-start mb-4">
                        <h3 id="popoverUnitName" class="text-xl font-bold text-gray-800"></h3>
                        <button onclick="hideUnitDetail()" class="text-gray-400 hover:text-gray-600 p-1">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-base font-medium text-gray-600">Unit Code:</span>
                            <span id="popoverUnitCode" class="text-base font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-base font-medium text-gray-600">House Type:</span>
                            <span id="popoverHouseType" class="text-base font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-base font-medium text-gray-600">Land Area:</span>
                            <span id="popoverLandArea" class="text-base font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-base font-medium text-gray-600">Building Area:</span>
                            <span id="popoverBuildingArea" class="text-base font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-base font-medium text-gray-600">Progress:</span>
                            <span id="popoverProgress" class="text-base font-semibold text-gray-900"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-base font-medium text-gray-600">Status:</span>
                            <span id="popoverStatus" class="px-3 py-1 text-sm font-bold rounded-full"></span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-base font-medium text-gray-600">Price:</span>
                            <span id="popoverPrice" class="text-base font-bold text-[#2FA769]"></span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <a id="popoverViewLink" href="#" class="flex-1 bg-[#2FA769] text-white text-center py-3 px-4 rounded-lg hover:bg-[#256f4a] transition duration-200 text-base font-semibold">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </a>
                        <a id="popoverEditLink" href="#" class="flex-1 bg-blue-500 text-white text-center py-3 px-4 rounded-lg hover:bg-blue-600 transition duration-200 text-base font-semibold">
                            <i class="fas fa-edit mr-2"></i>Edit Unit
                        </a>
                    </div>
                </div>

                <!-- Overlay for popover -->
                <div id="popoverOverlay" class="fixed inset-0 bg-black/20 bg-opacity-25 z-40 hidden" onclick="hideUnitDetail()"></div>

                    <!-- Instructions -->
                    <div class="mt-3 text-center text-sm text-gray-600">
                        <i class="fas fa-mouse-pointer mr-1"></i>
                        Use mouse wheel to zoom • Click and drag to pan • Click unit points for details • Touch and drag on mobile
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
@if($cluster->site_plan_path)
document.addEventListener('DOMContentLoaded', function() {
    let scale = 1;
    let translateX = 0;
    let translateY = 0;
    let isDragging = false;
    let startX, startY;
    let image = document.getElementById('zoomImage');
    let imageContainer = document.getElementById('imageContainer');
    let viewer = document.getElementById('viewer');
    let zoomLevelDisplay = document.getElementById('zoomLevel');
    let unitPoints = document.querySelectorAll('.unit-point');

    // Display image at original size (100%) on load
    image.onload = function() {
        const imgWidth = image.naturalWidth;
        const imgHeight = image.naturalHeight;
        
        // Set viewer size based on image dimensions
        setViewerSize(imgWidth, imgHeight);
        
        // Set scale to 1 (100% - original size)
        scale = 1;
        
        // Center the image in the viewer
        translateX = (viewer.offsetWidth - imgWidth) / 2;
        translateY = (viewer.offsetHeight - imgHeight) / 2;
        
        updateTransform();
        positionPoints(); // Position points after transform is set
    };

    // Function to set viewer size based on image dimensions
    function setViewerSize(imgWidth, imgHeight) {
        const maxWidth = window.innerWidth * 0.9; // 90% of viewport width
        const maxHeight = window.innerHeight * 0.8; // 80% of viewport height
        const minWidth = 400; // Minimum width
        const minHeight = 300; // Minimum height
        
        // Calculate optimal viewer size
        let viewerWidth = Math.min(imgWidth, maxWidth);
        let viewerHeight = Math.min(imgHeight, maxHeight);
        
        // Ensure minimum sizes
        viewerWidth = Math.max(viewerWidth, minWidth);
        viewerHeight = Math.max(viewerHeight, minHeight);
        
        // If image is smaller than min size, use image size
        if (imgWidth < minWidth && imgHeight < minHeight) {
            viewerWidth = imgWidth;
            viewerHeight = imgHeight;
        }
        
        // Apply size to viewer
        viewer.style.width = viewerWidth + 'px';
        viewer.style.height = viewerHeight + 'px';
    }

    // Handle image load error
    image.onerror = function() {
        console.error('Failed to load site plan image');
        viewer.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><i class="fas fa-exclamation-triangle text-2xl mr-2"></i>Failed to load image</div>';
    };

    // If already loaded
    if (image.complete) {
        image.onload();
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        if (image.complete) {
            const imgWidth = image.naturalWidth;
            const imgHeight = image.naturalHeight;
            
            // Resize viewer based on current scaled image size
            setViewerSize(imgWidth * scale, imgHeight * scale);
            
            // Adjust image position after viewer resize
            const viewerWidth = viewer.offsetWidth;
            const viewerHeight = viewer.offsetHeight;
            const scaledImgWidth = imgWidth * scale;
            const scaledImgHeight = imgHeight * scale;
            
            // Keep image centered if it's smaller than viewer
            if (scaledImgWidth < viewerWidth) {
                translateX = (viewerWidth - scaledImgWidth) / 2;
            }
            if (scaledImgHeight < viewerHeight) {
                translateY = (viewerHeight - scaledImgHeight) / 2;
            }
            
            updateTransform();
            positionPoints(); // Reposition points after resize
        }
    });

    function updateTransform() {
        imageContainer.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
        zoomLevelDisplay.textContent = Math.round(scale * 100) + '%';
        // No need to call positionPoints anymore since points are children of the transformed container
    }

    function positionPoints() {
        unitPoints.forEach(point => {
            const coords = point.getAttribute('data-coordinates');
            if (coords) {
                const [xPercent, yPercent] = coords.split(',').map(Number);
                // Convert percentages to pixel coordinates relative to the original image
                const imgWidth = image.naturalWidth;
                const imgHeight = image.naturalHeight;
                const x = xPercent * imgWidth;
                const y = yPercent * imgHeight;

                // Position relative to the image (since points are children of the same container)
                point.style.left = x + 'px';
                point.style.top = y + 'px';

                // Debug: uncomment to see coordinates
                // console.log(`Point: ${xPercent}, ${yPercent} -> ${x}, ${y}`);
            }
        });
    }

    // Zoom In
    window.zoomIn = function() {
        const oldScale = scale;
        scale += 0.2;
        if (scale > 5) scale = 5;
        
        // Adjust translation to maintain center point during zoom
        adjustTranslationForZoom(oldScale, scale);
        updateTransform();
    };

    // Zoom Out
    window.zoomOut = function() {
        const oldScale = scale;
        scale -= 0.2;
        if (scale < 0.1) scale = 0.1;
        
        // Adjust translation to maintain center point during zoom
        adjustTranslationForZoom(oldScale, scale);
        updateTransform();
    };

    // Function to adjust translation when zooming to maintain relative position
    function adjustTranslationForZoom(oldScale, newScale) {
        const scaleRatio = newScale / oldScale;
        const viewerCenterX = viewer.offsetWidth / 2;
        const viewerCenterY = viewer.offsetHeight / 2;
        
        // Calculate the offset from viewer center to current translation
        const offsetX = viewerCenterX - translateX;
        const offsetY = viewerCenterY - translateY;
        
        // Scale the offset and recalculate translation
        translateX = viewerCenterX - (offsetX * scaleRatio);
        translateY = viewerCenterY - (offsetY * scaleRatio);
    }

    // Reset Zoom - back to original size (100%)
    window.resetZoom = function() {
        const imgWidth = image.naturalWidth;
        const imgHeight = image.naturalHeight;
        
        // Reset to original size (100%)
        scale = 1;
        translateX = (viewer.offsetWidth - imgWidth) / 2;
        translateY = (viewer.offsetHeight - imgHeight) / 2;
        
        updateTransform();
        positionPoints(); // Reposition points after reset
    };

    // Mouse Wheel Zoom
    viewer.addEventListener('wheel', function(e) {
        e.preventDefault();
        
        const oldScale = scale;
        if (e.deltaY < 0) {
            scale += 0.1;
            if (scale > 5) scale = 5;
        } else {
            scale -= 0.1;
            if (scale < 0.1) scale = 0.1;
        }
        
        // Adjust translation to maintain zoom center
        adjustTranslationForZoom(oldScale, scale);
        updateTransform();
    });

    // Mouse Down - Start Dragging
    viewer.addEventListener('mousedown', function(e) {
        isDragging = true;
        startX = e.clientX - translateX;
        startY = e.clientY - translateY;
        viewer.classList.remove('cursor-grab');
        viewer.classList.add('cursor-grabbing');
    });

    // Mouse Move - Dragging
    viewer.addEventListener('mousemove', function(e) {
        if (isDragging) {
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            updateTransform();
        }
    });

    // Mouse Up - Stop Dragging
    viewer.addEventListener('mouseup', function() {
        isDragging = false;
        viewer.classList.remove('cursor-grabbing');
        viewer.classList.add('cursor-grab');
    });

    // Mouse Leave - Stop Dragging
    viewer.addEventListener('mouseleave', function() {
        isDragging = false;
        viewer.classList.remove('cursor-grabbing');
        viewer.classList.add('cursor-grab');
    });

    // Touch Support
    let touchStartX, touchStartY;

    viewer.addEventListener('touchstart', function(e) {
        const touch = e.touches[0];
        touchStartX = touch.clientX - translateX;
        touchStartY = touch.clientY - translateY;
    });

    viewer.addEventListener('touchmove', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        translateX = touch.clientX - touchStartX;
        translateY = touch.clientY - touchStartY;
        updateTransform();
    });

    // Unit Detail Popover Functions
    window.showUnitDetail = function(pointElement) {
        const unitId = pointElement.getAttribute('data-unit-id');
        const unitName = pointElement.getAttribute('data-unit-name');
        const unitCode = pointElement.getAttribute('data-unit-code');
        const houseType = pointElement.getAttribute('data-house-type');
        const landArea = pointElement.getAttribute('data-land-area');
        const buildingArea = pointElement.getAttribute('data-building-area');
        const progress = pointElement.getAttribute('data-progress');
        const status = pointElement.getAttribute('data-status');
        const price = pointElement.getAttribute('data-price');

        // Fill popover data
        document.getElementById('popoverUnitName').textContent = unitName;
        document.getElementById('popoverUnitCode').textContent = unitCode;
        document.getElementById('popoverHouseType').textContent = houseType;
        document.getElementById('popoverLandArea').textContent = landArea + ' m²';
        document.getElementById('popoverBuildingArea').textContent = buildingArea + ' m²';
        document.getElementById('popoverProgress').textContent = progress + '%';
        document.getElementById('popoverPrice').textContent = price;

        // Set status badge
        const statusElement = document.getElementById('popoverStatus');
        statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        statusElement.className = 'px-3 py-1 text-sm font-bold rounded-full';
        
        if (status === 'available') {
            statusElement.classList.add('bg-green-100', 'text-green-800');
        } else if (status === 'reserved') {
            statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
        } else if (status === 'booked') {
            statusElement.classList.add('bg-red-100', 'text-red-800');
        }

        // Set action links
        document.getElementById('popoverViewLink').href = `/units/${unitId}`;
        document.getElementById('popoverEditLink').href = `/units/${unitId}/edit`;

        // Position popover near the clicked point
        const rect = pointElement.getBoundingClientRect();
        const popover = document.getElementById('unitPopover');
        
        // Calculate position (center above the point)
        let left = rect.left + (rect.width / 2) - (popover.offsetWidth / 2);
        let top = rect.top - popover.offsetHeight - 10;

        // Ensure popover stays within viewport
        if (left < 10) left = 10;
        if (left + popover.offsetWidth > window.innerWidth - 10) {
            left = window.innerWidth - popover.offsetWidth - 10;
        }
        if (top < 10) {
            // If not enough space above, show below
            top = rect.bottom + 10;
        }

        popover.style.left = left + 'px';
        popover.style.top = top + 'px';

        // Show popover and overlay
        document.getElementById('unitPopover').classList.remove('hidden');
        document.getElementById('popoverOverlay').classList.remove('hidden');
    };

    window.hideUnitDetail = function() {
        document.getElementById('unitPopover').classList.add('hidden');
        document.getElementById('popoverOverlay').classList.add('hidden');
    };

    // Close popover when clicking outside
    document.addEventListener('click', function(e) {
        const popover = document.getElementById('unitPopover');
        const overlay = document.getElementById('popoverOverlay');
        
        if (!popover.contains(e.target) && !overlay.contains(e.target) && !e.target.classList.contains('unit-point')) {
            hideUnitDetail();
        }
    });
});
@endif
</script>
@endsection
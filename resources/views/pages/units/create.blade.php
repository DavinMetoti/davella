@extends('pages.layout')

@section('main')
<div class="mb-2">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Create New Unit</h1>
    <p class="text-gray-600">Add a new housing unit to the system</p>
</div>
<div class="max-w-full mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-8">

    <form method="POST" action="{{ route('units.store') }}" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="cluster_id" class="block text-sm font-semibold text-gray-700 mb-2">Cluster</label>
                <select name="cluster_id" id="cluster_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" required>
                    <option value="">Select a cluster</option>
                    @foreach($clusters as $cluster)
                        <option value="{{ $cluster->id }}" data-site-plan="{{ $cluster->site_plan_path ? Storage::url($cluster->site_plan_path) : '' }}" {{ old('cluster_id') == $cluster->id ? 'selected' : '' }}>
                            {{ $cluster->name }}
                        </option>
                    @endforeach
                </select>
                @error('cluster_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Unit Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="e.g., Rumah Type 36 Block A No. 001" required>
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Unit Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="block" class="block text-sm font-semibold text-gray-700 mb-2">Block</label>
                <input type="text" name="block" id="block" value="{{ old('block') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="e.g., A, B, C" required>
                @error('block') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="number" class="block text-sm font-semibold text-gray-700 mb-2">Number/Kavling</label>
                <input type="text" name="number" id="number" value="{{ old('number') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="e.g., 001, 002" required>
                @error('number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="house_type" class="block text-sm font-semibold text-gray-700 mb-2">House Type</label>
                <input type="text" name="house_type" id="house_type" value="{{ old('house_type') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="e.g., Type 36, Type 45" required>
                @error('house_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Area Information -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="land_area" class="block text-sm font-semibold text-gray-700 mb-2">Land Area (m²)</label>
                <input type="number" name="land_area" id="land_area" value="{{ old('land_area') }}" step="0.01"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="120.00" required>
                @error('land_area') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="building_area" class="block text-sm font-semibold text-gray-700 mb-2">Building Area (m²)</label>
                <input type="number" name="building_area" id="building_area" value="{{ old('building_area') }}" step="0.01"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="90.00" required>
                @error('building_area') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price (Rp)</label>
                <input type="text" name="price" id="price" value="{{ old('price') }}"
                       class="currency-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="500000000">
                @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Progress -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" id="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" required>
                    <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                </select>
                @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="progress" class="block text-sm font-semibold text-gray-700 mb-2">Construction Progress (%)</label>
                <input type="hidden" name="progress" id="progress" value="{{ old('progress', 0) }}">
                @error('progress') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                <!-- Interactive Progress Bar -->
                <div class="mt-3">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progress</span>
                        <span id="progress-text">0%</span>
                    </div>
                    <div id="progress-container" class="relative w-full bg-gray-200 rounded-full h-6 cursor-pointer">
                        <div id="progress-bar" class="bg-[#2FA769] h-6 rounded-full transition-all duration-300 flex items-center justify-end" style="width: 0%">
                            <div class="w-4 h-4 bg-white border-2 border-[#2FA769] rounded-full mr-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Site Plan Location -->
        <div id="site-plan-section" class="hidden space-y-4">
            <h3 class="text-lg font-semibold text-gray-700">Set Unit Location on Site Plan</h3>
            <input type="hidden" name="coordinates" id="coordinates">
            <div class="relative inline-block max-w-full">
                <img id="site-plan-image" src="" alt="Site Plan" class="w-full h-auto block border border-gray-300 rounded-lg">
                <div id="unit-point" class="absolute w-4 h-4 border-2 border-gray-800 rounded-full transform -translate-x-1/2 -translate-y-1/2 hidden pointer-events-none" style="top: 0; left: 0;"></div>
            </div>
            <p class="text-sm text-gray-500">Click on the site plan to set the unit location. Current coordinates: <span id="coord-display">Not set</span></p>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('units.index') }}"
               class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Unit
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const progressInput = document.getElementById('progress');
    let isDragging = false;

    // Initialize progress
    let currentProgress = parseInt(progressInput.value) || 0;
    updateProgress(currentProgress);

    function updateProgress(value) {
        value = Math.max(0, Math.min(100, value));
        progressBar.style.width = value + '%';
        progressText.textContent = value + '%';
        progressInput.value = value;
    }

    function getProgressFromMouse(e) {
        const rect = progressContainer.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const width = rect.width;
        return Math.round((x / width) * 100);
    }

    // Mouse events
    progressContainer.addEventListener('mousedown', function(e) {
        isDragging = true;
        const progress = getProgressFromMouse(e);
        updateProgress(progress);
    });

    document.addEventListener('mousemove', function(e) {
        if (isDragging) {
            const progress = getProgressFromMouse(e);
            updateProgress(progress);
        }
    });

    document.addEventListener('mouseup', function() {
        isDragging = false;
    });

    // Touch events for mobile
    progressContainer.addEventListener('touchstart', function(e) {
        isDragging = true;
        const touch = e.touches[0];
        const progress = getProgressFromMouse(touch);
        updateProgress(progress);
    });

    document.addEventListener('touchmove', function(e) {
        if (isDragging) {
            e.preventDefault();
            const touch = e.touches[0];
            const progress = getProgressFromMouse(touch);
            updateProgress(progress);
        }
    });

    document.addEventListener('touchend', function() {
        isDragging = false;
    });

    // Site Plan Functionality
    const clusterSelect = document.getElementById('cluster_id');
    const sitePlanSection = document.getElementById('site-plan-section');
    const sitePlanImage = document.getElementById('site-plan-image');
    const unitPoint = document.getElementById('unit-point');
    const coordinatesInput = document.getElementById('coordinates');
    const coordDisplay = document.getElementById('coord-display');
    const statusSelect = document.getElementById('status');

    // Update point color based on status
    statusSelect.addEventListener('change', function() {
        const status = this.value;
        console.log('Status changed to:', status);
        if (status === 'available') {
            unitPoint.style.backgroundColor = '#10B981'; // green-500
        } else if (status === 'reserved') {
            unitPoint.style.backgroundColor = '#F59E0B'; // yellow-500
        } else if (status === 'booked') {
            unitPoint.style.backgroundColor = '#EF4444'; // red-500
        }
        console.log('Point background color:', unitPoint.style.backgroundColor);
    });

    clusterSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const sitePlanUrl = selectedOption.getAttribute('data-site-plan');

        if (sitePlanUrl) {
            sitePlanImage.src = sitePlanUrl;
            sitePlanSection.classList.remove('hidden');
            // Reset point
            unitPoint.classList.add('hidden');
            coordinatesInput.value = '';
            coordDisplay.textContent = 'Not set';
        } else {
            sitePlanSection.classList.add('hidden');
            coordinatesInput.value = '';
        }
    });

    // Trigger on load if cluster is pre-selected
    if (clusterSelect.value) {
        clusterSelect.dispatchEvent(new Event('change'));
    }

    // Initialize point color on load
    statusSelect.dispatchEvent(new Event('change'));

    // Click on site plan
    sitePlanImage.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        
        console.log('Image width:', rect.width);
        console.log('Image height:', rect.height);
        
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const xPercent = x / rect.width;
        const yPercent = y / rect.height;

        // Position the point
        unitPoint.style.left = (xPercent * 100) + '%';
        unitPoint.style.top = (yPercent * 100) + '%';
        unitPoint.classList.remove('hidden');

        // Set coordinates
        coordinatesInput.value = xPercent.toFixed(4) + ',' + yPercent.toFixed(4);
        coordDisplay.textContent = (xPercent * 100).toFixed(2) + '%, ' + (yPercent * 100).toFixed(2) + '%';
    });
});

// Currency formatting for price input
document.addEventListener('DOMContentLoaded', function() {
    const currencyInputs = document.querySelectorAll('.currency-input');

    currencyInputs.forEach(input => {
        // Format initial value if exists
        if (input.value) {
            let value = input.value.replace(/[^\d]/g, '');
            input.dataset.rawValue = value; // Store raw value
            input.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
        }

        input.addEventListener('focus', function() {
            // Remove formatting for editing
            this.dataset.rawValue = this.value.replace(/[^\d]/g, '');
            this.value = this.dataset.rawValue;
        });

        input.addEventListener('input', function() {
            // Real-time formatting and store raw value
            let value = this.value.replace(/[^\d]/g, '');
            this.dataset.rawValue = value;
            if (value) {
                this.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
        });

        input.addEventListener('blur', function() {
            // Ensure formatting on blur
            let value = this.dataset.rawValue || '';
            if (value) {
                this.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
        });
    });

    // Strip formatting before form submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        currencyInputs.forEach(input => {
            input.value = input.dataset.rawValue || '';
        });
    });
});
</script>
@endsection
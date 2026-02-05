@extends('pages.layout')

@section('main')
<div class="mb-2">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Create New Cluster</h1>
    <p class="text-gray-600">Add a new housing cluster to the system</p>
</div>
<div class="max-w-full mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-8">

    <form method="POST" action="{{ route('clusters.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Cluster Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="Enter cluster name" required>
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="developer_id" class="block text-sm font-semibold text-gray-700 mb-2">Developer</label>
                <select name="developer_id" id="developer_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">Select a developer company</option>
                    @foreach(\App\Models\Company::active()->get() as $company)
                        <option value="{{ $company->id }}" {{ old('developer_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('developer_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Address and Description -->
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                <textarea name="address" id="address" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                          placeholder="Full address of the cluster" required>{{ old('address') }}</textarea>
                @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                          placeholder="Detailed description of the cluster">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Location Coordinates -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
                <input type="number" name="latitude" id="latitude" value="{{ old('latitude') }}" step="any"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="-6.2088">
                @error('latitude') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Optional: Latitude coordinate (e.g., -6.2088)</p>
            </div>

            <div>
                <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
                <input type="number" name="longitude" id="longitude" value="{{ old('longitude') }}" step="any"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="106.8456">
                @error('longitude') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Optional: Longitude coordinate (e.g., 106.8456)</p>
            </div>
        </div>

        <!-- Site Plan Upload -->
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="site_plan" class="block text-sm font-semibold text-gray-700 mb-2">Site Plan / Denah</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#2FA769] transition duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="site_plan" class="relative cursor-pointer bg-white rounded-md font-medium text-[#2FA769] hover:text-[#256f4a] focus-within:outline-none">
                                <span>Upload site plan</span>
                                <input id="site_plan" name="site_plan" type="file" accept="image/*" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @error('site_plan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

                <!-- Image Preview -->
                <div id="site_plan_preview" class="mt-4 hidden">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Preview:</p>
                    <img id="preview_image" src="" alt="Site Plan Preview" class="max-w-xs max-h-48 object-cover rounded-lg border border-gray-300">
                </div>
            </div>
        </div>

        <!-- Property Details -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="area_size" class="block text-sm font-semibold text-gray-700 mb-2">Area Size</label>
                <input type="text" name="area_size" id="area_size" value="{{ old('area_size') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="5000 m²">
                @error('area_size') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="total_units" class="block text-sm font-semibold text-gray-700 mb-2">Total Units</label>
                <input type="number" name="total_units" id="total_units" value="{{ old('total_units', 0) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       min="0">
                @error('total_units') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="available_units" class="block text-sm font-semibold text-gray-700 mb-2">Available Units</label>
                <input type="number" name="available_units" id="available_units" value="{{ old('available_units', 0) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       min="0">
                @error('available_units') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Price Range -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="price_range_min" class="block text-sm font-semibold text-gray-700 mb-2">Minimum Price</label>
                <input type="number" name="price_range_min" id="price_range_min" value="{{ old('price_range_min') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       min="0" step="1000000">
                @error('price_range_min') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Optional: Minimum price in Rupiah</p>
            </div>

            <div>
                <label for="price_range_max" class="block text-sm font-semibold text-gray-700 mb-2">Maximum Price</label>
                <input type="number" name="price_range_max" id="price_range_max" value="{{ old('price_range_max') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       min="0" step="1000000">
                @error('price_range_max') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Optional: Maximum price in Rupiah</p>
            </div>
        </div>

        <!-- Year Built -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="year_built" class="block text-sm font-semibold text-gray-700 mb-2">Year Built</label>
                <input type="number" name="year_built" id="year_built" value="{{ old('year_built') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       min="1900" max="{{ date('Y') + 1 }}">
                @error('year_built') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Facilities</label>
                <div class="space-y-2">
                    @php
                        $facilities = ['Swimming Pool', 'Gym', 'Security 24/7', 'Parking Area', 'Garden', 'Playground', 'Mosque', 'School', 'Mall', 'Hospital'];
                        $oldFacilities = old('facilities', []);
                    @endphp
                    @foreach($facilities as $facility)
                        <label class="inline-flex items-center mr-4">
                            <input type="checkbox" name="facilities[]" value="{{ $facility }}"
                                   {{ in_array($facility, $oldFacilities) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-[#2FA769] focus:ring-[#2FA769]">
                            <span class="ml-2 text-sm text-gray-700">{{ $facility }}</span>
                        </label>
                    @endforeach
                </div>
                @error('facilities') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Active Status -->
        <div class="flex items-center space-x-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                   class="w-5 h-5 text-[#2FA769] bg-gray-100 border-gray-300 rounded focus:ring-[#2FA769] focus:ring-2">
            <label for="is_active" class="text-sm font-semibold text-gray-700">Cluster is active</label>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('clusters.index') }}"
               class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Cluster
            </button>
        </div>
    </form>
</div>

<script>
document.getElementById('site_plan').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('site_plan_preview');
    const previewImage = document.getElementById('preview_image');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});
</script>
@endsection
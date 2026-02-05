@extends('pages.layout')

@section('main')
<div class="mb-2">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Create New Menu</h1>
    <p class="text-gray-600">Add a new menu item to the navigation system</p>
</div>
<div class="max-w-full mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-8">

    <form method="POST" action="{{ route('menus.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Menu Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" 
                       placeholder="Enter menu name" required>
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">Icon Class</label>
                <div class="relative">
                    <div class="relative">
                        <input type="text" id="icon-search" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white pr-10" 
                               placeholder="Search Font Awesome icons...">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <div id="icon-dropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden">
                        <div id="icon-list" class="py-1">
                            <!-- Icons will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                <input type="hidden" name="icon" id="icon" value="{{ old('icon') }}">
                @error('icon') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="route" class="block text-sm font-semibold text-gray-700 mb-2">Route Name</label>
                <input type="text" name="route" id="route" value="{{ old('route') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" 
                       placeholder="dashboard">
                @error('route') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="order" class="block text-sm font-semibold text-gray-700 mb-2">Display Order</label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" 
                       min="0">
                @error('order') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="permission_id" class="block text-sm font-semibold text-gray-700 mb-2">Required Permission</label>
                <select name="permission_id" id="permission_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">No permission required</option>
                    @foreach($permissions as $permission)
                        <option value="{{ $permission->id }}" {{ old('permission_id') == $permission->id ? 'selected' : '' }}>
                            {{ $permission->name }}
                        </option>
                    @endforeach
                </select>
                @error('permission_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-2">Parent Menu</label>
                <select name="parent_id" id="parent_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">No parent (main menu)</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" {{ old('parent_id') == $menu->id ? 'selected' : '' }}>
                            {{ $menu->name }}
                        </option>
                    @endforeach
                </select>
                @error('parent_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                   class="w-5 h-5 text-[#2FA769] bg-gray-100 border-gray-300 rounded focus:ring-[#2FA769] focus:ring-2">
            <label for="is_active" class="text-sm font-semibold text-gray-700">Menu is active</label>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('menus.index') }}" 
               class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Menu
            </button>
        </div>
    </form>
</div>
@endsection
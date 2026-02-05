@extends('pages.layout')

@section('main')
<div class="mb-2">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Company</h1>
    <p class="text-gray-600">Update company information</p>
</div>
<div class="max-w-full mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-8">

    <form method="POST" action="{{ route('companies.update', $company) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Company Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="Enter company name" required>
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $company->slug) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="company-slug" required>
                @error('slug') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">URL-friendly identifier for the company</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }}
                   class="w-5 h-5 text-[#2FA769] bg-gray-100 border-gray-300 rounded focus:ring-[#2FA769] focus:ring-2">
            <label for="is_active" class="text-sm font-semibold text-gray-700">Company is active</label>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('companies.index') }}"
               class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Update Company
            </button>
        </div>
    </form>
</div>
@endsection
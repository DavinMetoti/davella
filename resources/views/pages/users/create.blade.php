@extends('pages.layout')

@section('main')
<div class="mb-2">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Create New User</h1>
    <p class="text-gray-600">Add a new user to the system</p>
</div>
<div class="max-w-full mx-auto bg-white rounded-xl shadow-lg border border-gray-200 p-8">

    <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="Enter full name" required>
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="user@example.com" required>
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="Enter password" required>
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="Confirm password" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="company_id" class="block text-sm font-semibold text-gray-700 mb-2">Company</label>
                <select name="company_id" id="company_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">No company assigned</option>
                    @foreach(\App\Models\Company::active()->get() as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                <select name="role" id="role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">Select a role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="viewer" {{ old('role') == 'viewer' ? 'selected' : '' }}>Viewer</option>
                </select>
                @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                   class="w-5 h-5 text-[#2FA769] bg-gray-100 border-gray-300 rounded focus:ring-[#2FA769] focus:ring-2">
            <label for="is_active" class="text-sm font-semibold text-gray-700">User is active</label>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('users.index') }}"
               class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create User
            </button>
        </div>
    </form>
</div>
@endsection
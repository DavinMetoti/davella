@extends('pages.layout')

@section('main')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Profile Settings</h1>
            <p class="text-gray-600">Manage your account settings and preferences</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Photo Section -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Photo</h2>

            <!-- Current Photo -->
            <div class="flex flex-col items-center mb-6">
                <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mb-4 overflow-hidden">
                    @if($user->profile_photo)
                        <img src="{{ $user->profile_photo_url }}"
                             alt="Profile Photo"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-[#2FA769] flex items-center justify-center text-white text-4xl font-bold">
                            {{ $user->initials }}
                        </div>
                    @endif
                </div>

                <div class="flex space-x-2 items-center">
                    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="inline">
                        @csrf
                        <label for="profile_photo" class="cursor-pointer px-4 py-2.5 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 text-sm font-medium border-0 outline-none">
                            <i class="fas fa-camera mr-2"></i>Change Photo
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="hidden" onchange="this.form.submit()">
                    </form>

                    @if($user->profile_photo)
                        <form method="POST" action="{{ route('profile.photo.remove') }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 text-sm font-medium border-0 outline-none">
                                <i class="fas fa-trash mr-2"></i>Remove
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Photo Guidelines -->
            <div class="text-sm text-gray-600">
                <p class="mb-2"><strong>Guidelines:</strong></p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Maximum file size: 2MB</li>
                    <li>Supported formats: JPG, PNG, GIF</li>
                    <li>Square images work best</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                               required>
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                               required>
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                               placeholder="+62 812-3456-7890">
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                        @error('date_of_birth') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                    <textarea name="address" id="address" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                              placeholder="Enter your address">{{ old('address', $user->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">Bio</label>
                    <textarea name="bio" id="bio" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                              placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Password</h2>

            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           required>
                    @error('current_password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                               required>
                        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                               required>
                        @error('password_confirmation') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Account Status</label>
                    <div class="mt-1">
                        @if($user->is_active)
                            <span class="px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full">Inactive</span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Role</label>
                    <div class="mt-1">
                        @if($user->roles->count() > 0)
                            <span class="px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full">{{ $user->roles->first()->name }}</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">No Role</span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Member Since</label>
                    <p class="mt-1 text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Last Login</label>
                    <p class="mt-1 text-gray-900">
                        @if($user->last_login_at)
                            {{ $user->last_login_at->format('M d, Y H:i') }}
                        @else
                            Never
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <span>Please check the form for errors.</span>
        </div>
    </div>
@endif

<script>
// Auto-hide messages after 5 seconds
setTimeout(function() {
    const messages = document.querySelectorAll('.fixed.top-20');
    messages.forEach(message => {
        message.style.display = 'none';
    });
}, 5000);
</script>
@endsection
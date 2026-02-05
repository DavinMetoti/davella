<form method="POST" action="{{ route('login.post') }}">
    @csrf

    <div class="mb-6 relative">
        <label for="email" class="block text-gray-300 font-medium mb-2">Email Address</label>
        <div class="relative">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="w-full pl-10 pr-3 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2FA769] focus:border-transparent transition duration-300">
            <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
        @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6 relative">
        <label for="password" class="block text-gray-300 font-medium mb-2">Password</label>
        <div class="relative">
            <input id="password" type="password" name="password" required
                   class="w-full pl-10 pr-3 py-3 bg-gray-700 border border-gray-600 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2FA769] focus:border-transparent transition duration-300">
            <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
        @error('password')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember" class="mr-2 h-4 w-4 text-[#2FA769] focus:ring-[#2FA769] rounded bg-gray-700 border-gray-600">
            <label for="remember" class="text-gray-300 text-sm">Remember me</label>
        </div>
        <a href="#" class="text-sm text-[#2FA769] hover:text-green-400">Forgot password?</a>
    </div>

    <button type="submit" class="w-full bg-gradient-to-r from-[#2FA769] to-green-600 text-white py-3 px-4 rounded-lg hover:from-green-600 hover:to-[#2FA769] focus:outline-none focus:ring-2 focus:ring-[#2FA769] focus:ring-offset-2 transition duration-300 shadow-lg">
        <i class="fas fa-sign-in-alt mr-2"></i>Login
    </button>

    <p class="text-center mt-4 text-gray-300">
        Don't have an account? <a href="/register" class="text-[#2FA769] hover:text-green-400 font-medium">Register here</a>
    </p>
</form>
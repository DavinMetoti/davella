<nav class="fixed top-0 left-0 right-0 z-10 bg-[#2FA769] shadow-lg">
    <div class="px-4 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo and Hamburger Menu -->
            <div class="flex items-center space-x-4">
                <button class="hamburger-btn text-white hover:text-gray-200 transition-colors duration-200 lg:hidden">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-[#2FA769] text-sm"></i>
                    </div>
                    <span class="font-bold text-white text-lg hidden sm:block">{{ config('app.name', 'PropertyMS') }}</span>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="flex items-center space-x-3">
                <!-- Notifications (Optional) -->
                <button class="relative p-2 text-white hover:text-gray-200 transition-colors duration-200 hidden md:block">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full text-xs flex items-center justify-center text-white">3</span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-[#256f4a] transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                        <!-- User Avatar -->
                        <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-[#2FA769] font-semibold text-sm">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                            @else
                                {{ auth()->user()->initials }}
                            @endif
                        </div>

                        <!-- User Info (Desktop) -->
                        <div class="hidden md:block text-left">
                            <div class="text-sm font-medium text-white">
                                @if(auth()->user())
                                    {{ auth()->user()->name }}
                                @else
                                    User
                                @endif
                            </div>
                            <div class="text-xs text-gray-200">
                                @if(auth()->user() && auth()->user()->roles->count() > 0)
                                    {{ auth()->user()->roles->first()->name }}
                                @else
                                    User
                                @endif
                            </div>
                        </div>

                        <i class="fas fa-chevron-down text-white text-sm transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                         x-cloak>

                        <!-- User Info Header -->
                        <div class="px-4 py-3 border-b border-gray-200">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-[#2FA769] rounded-full flex items-center justify-center text-white font-semibold">
                                    @if(auth()->user()->profile_photo)
                                        <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                                    @else
                                        {{ auth()->user()->initials }}
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-800">
                                        @if(auth()->user())
                                            {{ auth()->user()->name }}
                                        @else
                                            User
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        @if(auth()->user())
                                            {{ auth()->user()->email }}
                                        @else
                                            user@example.com
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-user mr-3 w-4 text-center"></i>
                                Profile Settings
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-cog mr-3 w-4 text-center"></i>
                                Account Settings
                            </a>
                            <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200">
                                <i class="fas fa-question-circle mr-3 w-4 text-center"></i>
                                Help & Support
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200"></div>

                        <!-- Logout -->
                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt mr-3 w-4 text-center"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu hidden lg:hidden bg-[#2FA769] border-t border-white border-opacity-20">
        <div class="px-4 py-2 space-y-1">
            <a href="{{ route('profile.edit') }}"
               class="mobile-nav-link flex items-center px-3 py-3 rounded-lg text-sm font-medium text-white hover:bg-[#256f4a] transition-colors duration-200">
                <i class="fas fa-user mr-3 w-5 text-center"></i>Profile Settings
            </a>

            <a href="#"
               class="mobile-nav-link flex items-center px-3 py-3 rounded-lg text-sm font-medium text-white hover:bg-[#256f4a] transition-colors duration-200">
                <i class="fas fa-cog mr-3 w-5 text-center"></i>Account Settings
            </a>

            <a href="#"
               class="mobile-nav-link flex items-center px-3 py-3 rounded-lg text-sm font-medium text-white hover:bg-[#256f4a] transition-colors duration-200">
                <i class="fas fa-question-circle mr-3 w-5 text-center"></i>Help & Support
            </a>

            <div class="border-t border-white border-opacity-20 my-2"></div>

            <form method="POST" action="{{ route('logout') }}" class="inline w-full">
                @csrf
                <button type="submit" class="mobile-nav-link flex items-center w-full px-3 py-3 rounded-lg text-sm font-medium text-white hover:bg-red-600 transition-colors duration-200">
                    <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>Logout
                </button>
            </form>
        </div>
    </div>
</nav>
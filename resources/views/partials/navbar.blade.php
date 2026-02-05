<nav class="fixed top-0 left-0 right-0 z-10 bg-[#2FA769] shadow-lg">
    <div class="px-4 py-3">
        <div class="flex justify-between items-center">
            <div class="flex space-x-7">
                <button class="hamburger-btn text-white mr-4">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="font-semibold text-white text-lg">{{ config('app.name', 'Laravel') }}</span>
            </div>
            <!-- Secondary Navbar items -->
            <div class="hidden md:flex items-center space-x-3">
                <a href="#" class="py-2 px-2 font-medium text-white rounded hover:bg-white hover:text-[#2FA769] transition duration-300">Profile</a>
                <a href="#" class="py-2 px-2 font-medium text-[#2FA769] bg-white rounded hover:bg-gray-200 transition duration-300">Logout</a>
            </div>
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button class="outline-none mobile-menu-button">
                    <svg class="w-6 h-6 text-white hover:text-gray-200"
                         x-show="!showMenu"
                         fill="none"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- mobile menu -->
    <div class="hidden mobile-menu">
        <ul class="">
            <li class="active"><a href="#" class="block text-sm px-2 py-4 text-white bg-[#2FA769] font-semibold">Dashboard</a></li>
            <li><a href="#" class="block text-sm px-2 py-4 hover:bg-[#2FA769] transition duration-300">Users</a></li>
            <li><a href="#" class="block text-sm px-2 py-4 hover:bg-[#2FA769] transition duration-300">Reports</a></li>
            <li><a href="#" class="block text-sm px-2 py-4 hover:bg-[#2FA769] transition duration-300">Settings</a></li>
        </ul>
    </div>
</nav>
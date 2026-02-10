@php
    use App\Models\Menu;
    $menus = Menu::active()->whereNull('parent_id')->with(['children', 'permission'])->ordered()->get();
@endphp

<aside>
    <div class="p-4">
        <ul class="space-y-2">
            @foreach($menus as $menu)
                @php
                    $hasAccess = true;
                    if ($menu->name === 'Sales Report') {
                        // Special check for Sales Report - only super_admin and Owner can access
                        $hasAccess = auth()->user()->hasRole(['super_admin', 'Owner']);
                    } elseif ($menu->name === 'Menus') {
                        // Special check for Menus - only super_admin can access
                        $hasAccess = auth()->user()->hasRole('super_admin');
                    } elseif (!is_null($menu->permission)) {
                        $hasAccess = auth()->user()->can($menu->permission->name);
                    }
                @endphp
                @if($hasAccess)
                    <li>
                        <a href="{{ $menu->route === '#' ? '#' : route($menu->route) }}" class="flex items-center py-2 px-4 rounded-lg {{ ($menu->route !== '#' && request()->routeIs($menu->route)) ? 'bg-[#2FA769] text-white' : 'hover:bg-gray-200 transition duration-300' }}">
                            <i class="{{ $menu->icon }} mr-2"></i> {{ $menu->name }}
                        </a>
                        @if($menu->children->count() > 0)
                            <ul class="ml-4 mt-2 space-y-1">
                                @foreach($menu->children as $child)
                                    @if(is_null($child->permission) || auth()->user()->can($child->permission->name ?? ''))
                                        <li>
                                            <a href="{{ $child->route === '#' ? '#' : route($child->route) }}" class="flex items-center py-1 px-3 rounded {{ ($child->route !== '#' && request()->routeIs($child->route)) ? 'bg-[#2FA769] text-white' : 'hover:bg-gray-200 transition duration-300' }}">
                                                <i class="{{ $child->icon }} mr-2 text-sm"></i> {{ $child->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
            <li>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center py-2 px-4 rounded-lg hover:bg-gray-200 transition duration-300 w-full text-left">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
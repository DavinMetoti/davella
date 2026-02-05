<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\Models\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function __construct(private MenuRepositoryInterface $menuRepository)
    {
    }

    public function index(): View
    {
        return view('pages.menus.index');
    }

    public function api(Request $request)
    {
        $menus = $this->menuRepository->getAll();

        return DataTables::of($menus)
            ->addColumn('permission_name', function ($menu) {
                return $menu->permission ? $menu->permission->name : 'N/A';
            })
            ->addColumn('parent_name', function ($menu) {
                return $menu->parent ? $menu->parent->name : 'N/A';
            })
            ->addColumn('actions', function ($menu) {
                return '
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700 p-1 rounded" onclick="toggleDropdown(' . $menu->id . ')">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="dropdown-' . $menu->id . '" class="absolute right-0 mt-1 w-32 bg-white border border-gray-200 rounded shadow-lg z-10 hidden">
                            <a href="' . route('menus.edit', $menu) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit mr-2 text-blue-500"></i> Edit
                            </a>
                            <form method="POST" action="' . route('menus.destroy', $menu) . '" class="inline">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-trash-alt mr-2 text-red-500"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create(): View
    {
        $permissions = ModelsPermission::all();
        $menus = Menu::whereNull('parent_id')->get();
        return view('pages.menus.create', compact('permissions', 'menus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'permission_id' => 'nullable|exists:permissions,id',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $this->menuRepository->create($validated);

        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function show(Menu $menu): View
    {
        return view('pages.menus.show', compact('menu'));
    }

    public function edit(Menu $menu): View
    {
        $permissions = ModelsPermission::all();
        $menus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        return view('pages.menus.edit', compact('menu', 'permissions', 'menus'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'permission_id' => 'nullable|exists:permissions,id',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $this->menuRepository->update($menu->id, $validated);

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $this->menuRepository->delete($menu->id);

        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }
}

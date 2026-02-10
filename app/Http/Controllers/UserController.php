<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): View
    {
        return view('pages.users.index');
    }

    public function api(Request $request)
    {
        $users = $this->userRepository->getAll();

        // Filter out super admin users for Owner role
        if (auth()->user()->hasRole('Owner')) {
            $users = $users->filter(function ($user) {
                return !$user->hasRole('super_admin');
            });
        }

        return DataTables::of($users)
            ->addColumn('role', function ($user) {
                $role = $user->roles->first()->name ?? 'No Role';
                $badgeClass = 'bg-gray-100 text-gray-800';
                if ($role === 'super_admin') $badgeClass = 'bg-red-100 text-red-800';
                else if ($role === 'Owner') $badgeClass = 'bg-blue-100 text-blue-800';
                else if ($role === 'sales') $badgeClass = 'bg-green-100 text-green-800';
                return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $role)) . '</span>';
            })
            ->addColumn('is_active', function ($user) {
                return $user->is_active ? 
                    '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>' : 
                    '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>';
            })
            ->addColumn('actions', function ($user) {
                return '
                    <div class="dropdown-actions relative">
                        <button class="text-gray-500 hover:text-gray-700 p-1 rounded" onclick="toggleDropdown(' . $user->id . ')">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="dropdown-' . $user->id . '" class="absolute right-0 mt-1 w-32 bg-white border border-gray-200 rounded shadow-lg z-50 hidden">
                            <a href="' . route('users.edit', $user) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit mr-2 text-blue-500"></i> Edit
                            </a>
                            <form method="POST" action="' . route('users.destroy', $user) . '" class="inline">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-trash-alt mr-2 text-red-500"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['role', 'is_active', 'actions'])
            ->make(true);
    }

    public function create(): View
    {
        return view('pages.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Prevent Owner users from creating super admin users
        if (auth()->user()->hasRole('Owner') && $request->role === 'super_admin') {
            return redirect()->back()->withErrors(['role' => 'You do not have permission to create super admin users.'])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string|in:super_admin,Owner,sales',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);

        // Assign role if provided
        if (!empty($data['role'])) {
            $user->assignRole($data['role']);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show($id): View
    {
        $user = $this->userRepository->findById($id);

        // Prevent Owner users from viewing super admin users
        if (auth()->user()->hasRole('Owner') && $user->hasRole('super_admin')) {
            abort(403, 'You do not have permission to view super admin users.');
        }

        return view('pages.users.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = $this->userRepository->findById($id);

        // Prevent Owner users from editing super admin users
        if (auth()->user()->hasRole('Owner') && $user->hasRole('super_admin')) {
            abort(403, 'You do not have permission to edit super admin users.');
        }

        return view('pages.users.edit', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $user = $this->userRepository->findById($id);

        // Prevent Owner users from editing super admin users
        if (auth()->user()->hasRole('Owner') && $user->hasRole('super_admin')) {
            abort(403, 'You do not have permission to edit super admin users.');
        }

        // Prevent Owner users from assigning super admin role
        if (auth()->user()->hasRole('Owner') && $request->role === 'super_admin') {
            return redirect()->back()->withErrors(['role' => 'You do not have permission to assign super admin role.'])->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|string|in:super_admin,Owner,sales',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepository->update($user, $data);

        // Update role if provided
        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = $this->userRepository->findById($id);

        // Prevent Owner users from deleting super admin users
        if (auth()->user()->hasRole('Owner') && $user->hasRole('super_admin')) {
            abort(403, 'You do not have permission to delete super admin users.');
        }

        $this->userRepository->delete($user);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

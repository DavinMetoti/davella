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

        return DataTables::of($users)
            ->addColumn('role', function ($user) {
                $role = $user->roles->first()->name ?? 'No Role';
                $badgeClass = 'badge-primary';
                if ($role === 'super_admin') $badgeClass = 'badge-danger';
                else if ($role === 'admin') $badgeClass = 'badge-warning';
                else if ($role === 'owner') $badgeClass = 'badge-success';
                else if ($role === 'member') $badgeClass = 'badge-info';
                return '<span class="badge ' . $badgeClass . '">' . ucfirst(str_replace('_', ' ', $role)) . '</span>';
            })
            ->addColumn('company_name', function ($user) {
                return $user->company ? $user->company->name : 'N/A';
            })
            ->addColumn('actions', function ($user) {
                return '
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700 p-1 rounded" onclick="toggleDropdown(' . $user->id . ')">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="dropdown-' . $user->id . '" class="absolute right-0 mt-1 w-32 bg-white border border-gray-200 rounded shadow-lg z-10 hidden">
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
            ->rawColumns(['role', 'actions'])
            ->make(true);
    }

    public function create(): View
    {
        return view('pages.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'company_id' => 'nullable|exists:companies,id',
            'role' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);

        $this->userRepository->create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show($id): View
    {
        $user = $this->userRepository->findById($id);
        return view('pages.users.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = $this->userRepository->findById($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'company_id' => 'nullable|exists:companies,id',
            'role' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $user = $this->userRepository->findById($id);
        $data = $request->all();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepository->update($user, $data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = $this->userRepository->findById($id);
        $this->userRepository->delete($user);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

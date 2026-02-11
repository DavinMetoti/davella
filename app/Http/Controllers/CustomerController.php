<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'ktp_number' => 'nullable|string|max:20|unique:customers',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'notes' => 'nullable|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer): View
    {
        $customer->load('reservations');
        return view('pages.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer): View
    {
        return view('pages.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'ktp_number' => 'nullable|string|max:20|unique:customers,ktp_number,' . $customer->id,
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'notes' => 'nullable|string',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * API endpoint for DataTables
     */
    public function api(Request $request)
    {
        $customers = Customer::query();

        return DataTables::of($customers)
            ->addColumn('phone', function ($customer) {
                return $customer->phone ?: '<span class="text-gray-400">Not provided</span>';
            })
            ->addColumn('email', function ($customer) {
                return $customer->email ?: '<span class="text-gray-400">Not provided</span>';
            })
            ->addColumn('ktp_number', function ($customer) {
                return $customer->ktp_number ?: '<span class="text-gray-400">Not provided</span>';
            })
            ->addColumn('gender', function ($customer) {
                return $customer->gender ? ucfirst($customer->gender) : '<span class="text-gray-400">Not specified</span>';
            })
            ->addColumn('address', function ($customer) {
                return $customer->address ?: '<span class="text-gray-400">Not provided</span>';
            })
            ->addColumn('reservations_count', function ($customer) {
                $count = $customer->reservations()->count();
                return '<span class="font-medium">' . $count . '</span>';
            })
            ->addColumn('actions', function ($customer) {
                return '
                    <div class="dropdown-actions">
                        <button id="dropdownMenuIconButton-' . $customer->id . '" data-dropdown-toggle="dropdownDots-' . $customer->id . '" class="text-gray-700 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm p-2 focus:outline-none" type="button" onclick="toggleDropdown(' . $customer->id . ')">
                            <i class="fas fa-ellipsis"></i>
                        </button>
                        <div id="dropdown-' . $customer->id . '" class="dropdown-menu hidden">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton-' . $customer->id . '">
                                <li>
                                    <a href="' . route('customers.show', $customer) . '" class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900">View</a>
                                </li>
                                <li>
                                    <a href="' . route('customers.edit', $customer) . '" class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900">Edit</a>
                                </li>
                            </ul>
                            <div class="py-1 border-t border-gray-200">
                                <form method="POST" action="' . route('customers.destroy', $customer) . '" class="inline">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['reservations_count', 'actions'])
            ->make(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.units.index');
    }

    /**
     * API endpoint for DataTables
     */
    public function api(Request $request)
    {
        $units = Unit::with('cluster');

        return DataTables::of($units)
            ->addColumn('cluster_name', function ($unit) {
                return $unit->cluster ? $unit->cluster->name : 'N/A';
            })
            ->addColumn('name', function ($unit) {
                return $unit->name;
            })
            ->addColumn('unit_code', function ($unit) {
                return $unit->block . '/' . $unit->number;
            })
            ->addColumn('house_type', function ($unit) {
                return $unit->house_type;
            })
            ->addColumn('land_area_formatted', function ($unit) {
                return $unit->formatted_land_area;
            })
            ->addColumn('building_area_formatted', function ($unit) {
                return $unit->formatted_building_area;
            })
            ->addColumn('progress_percentage', function ($unit) {
                return $unit->progress_percentage;
            })
            ->addColumn('status_badge', function ($unit) {
                return $unit->status_badge;
            })
            ->addColumn('coordinates_status', function ($unit) {
                if (empty($unit->coordinates)) {
                    return '<span class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">Not Pin</span>';
                } else {
                    return '<span class="px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full">Pinned</span>';
                }
            })
            ->addColumn('price_formatted', function ($unit) {
                if ($unit->price) {
                    return 'Rp ' . number_format($unit->price, 0, ',', '.');
                } else {
                    return '<span class="text-gray-500">Price not set</span>';
                }
            })
            ->addColumn('actions', function ($unit) {
                return '
                    <div class="relative dropdown-actions">
                        <button type="button" class="text-gray-500 hover:text-gray-700 p-1 rounded focus:outline-none dropdown-toggle" data-menu-id="' . $unit->id . '">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="dropdown-' . $unit->id . '" class="absolute right-0 mt-1 w-32 bg-white border border-gray-200 rounded shadow-lg z-50 hidden">
                            <a href="' . route('units.show', $unit) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-eye mr-2 text-blue-500"></i> View
                            </a>
                            <a href="' . route('units.edit', $unit) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit mr-2 text-blue-500"></i> Edit
                            </a>
                            <form method="POST" action="' . route('units.destroy', $unit) . '" class="m-0">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none" onclick="return confirm(\'Are you sure you want to delete this unit?\')">
                                    <i class="fas fa-trash-alt mr-2 text-red-500"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['status_badge', 'coordinates_status', 'price_formatted', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $clusters = Cluster::active()->get();
        return view('pages.units.create', compact('clusters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'price' => $request->price ? preg_replace('/[^\d]/', '', $request->price) : null,
        ]);

        $request->validate([
            'cluster_id' => 'required|exists:clusters,id',
            'name' => 'required|string|max:255',
            'block' => 'required|string|max:10',
            'number' => 'required|string|max:10',
            'house_type' => 'required|string|max:50',
            'land_area' => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|in:available,reserved,booked',
            'coordinates' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        $unit = Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit): View
    {
        return view('pages.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit): View
    {
        $clusters = Cluster::active()->get();
        return view('pages.units.edit', compact('unit', 'clusters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $request->validate([
            'cluster_id' => 'required|exists:clusters,id',
            'name' => 'required|string|max:255',
            'block' => 'required|string|max:10',
            'number' => 'required|string|max:10',
            'house_type' => 'required|string|max:50',
            'land_area' => 'required|numeric|min:0',
            'building_area' => 'required|numeric|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|in:available,reserved,booked',
            'coordinates' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        $cluster = $unit->cluster;
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}

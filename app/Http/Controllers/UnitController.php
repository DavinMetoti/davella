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
            ->addColumn('actions', function ($unit) {
                return '
                    <div class="dropdown-actions">
                        <button id="dropdownMenuIconButton-' . $unit->id . '" data-dropdown-toggle="dropdownDots-' . $unit->id . '" class="text-gray-700 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm p-2 focus:outline-none" type="button" onclick="toggleDropdown(' . $unit->id . ')">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="dropdown-' . $unit->id . '" class="dropdown-menu hidden">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton-' . $unit->id . '">
                                <li>
                                    <a href="' . route('units.show', $unit) . '" class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900">View</a>
                                </li>
                                <li>
                                    <a href="' . route('units.edit', $unit) . '" class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900">Edit</a>
                                </li>
                            </ul>
                            <div class="py-1 border-t border-gray-200">
                                <form method="POST" action="' . route('units.destroy', $unit) . '" class="inline">
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
            ->rawColumns(['status_badge', 'coordinates_status', 'actions'])
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
        ]);

        $unit = Unit::create($request->all());
        $unit->cluster->updateStats();

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
        ]);

        $unit->update($request->all());
        $unit->cluster->updateStats();

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit): RedirectResponse
    {
        $cluster = $unit->cluster;
        $unit->delete();
        $cluster->updateStats();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}

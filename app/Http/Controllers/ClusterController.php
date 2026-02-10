<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ClusterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.clusters.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pages.clusters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'site_plan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'area_size' => 'nullable|string|max:255',
            'total_units' => 'nullable|integer|min:0',
            'available_units' => 'nullable|integer|min:0',
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'facilities' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('site_plan')) {
            $file = $request->file('site_plan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('site-plans', $filename, 'public');
            $data['site_plan_path'] = $path;
        }

        // Convert facilities array to JSON
        if (isset($data['facilities'])) {
            $data['facilities'] = json_encode($data['facilities']);
        }

        Cluster::create($data);

        return redirect()->route('clusters.index')->with('success', 'Cluster created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cluster $cluster): View
    {
        $cluster->load('units');
        return view('pages.clusters.show', compact('cluster'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cluster $cluster): View
    {
        return view('pages.clusters.edit', compact('cluster'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cluster $cluster): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'site_plan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'area_size' => 'nullable|string|max:255',
            'total_units' => 'nullable|integer|min:0',
            'available_units' => 'nullable|integer|min:0',
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0',
            'year_built' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'facilities' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('site_plan')) {
            // Delete old file if exists
            if ($cluster->site_plan_path && Storage::disk('public')->exists($cluster->site_plan_path)) {
                Storage::disk('public')->delete($cluster->site_plan_path);
            }

            $file = $request->file('site_plan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('site-plans', $filename, 'public');
            $data['site_plan_path'] = $path;
        }

        // Convert facilities array to JSON
        if (isset($data['facilities'])) {
            $data['facilities'] = json_encode($data['facilities']);
        }

        $cluster->update($data);

        return redirect()->route('clusters.index')->with('success', 'Cluster updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cluster $cluster): RedirectResponse
    {
        // Delete associated file if exists
        if ($cluster->site_plan_path && Storage::disk('public')->exists($cluster->site_plan_path)) {
            Storage::disk('public')->delete($cluster->site_plan_path);
        }

        $cluster->delete();

        return redirect()->route('clusters.index')->with('success', 'Cluster deleted successfully.');
    }

    /**
     * API endpoint for DataTables
     */
    public function api(Request $request)
    {
        $clusters = Cluster::query();

        return DataTables::of($clusters)
            ->addColumn('site_plan', function ($cluster) {
                if ($cluster->site_plan_path) {
                    return '<img src="' . Storage::url($cluster->site_plan_path) . '" alt="Site Plan" class="w-16 h-16 object-cover rounded">';
                }
                return '<span class="text-gray-400">No image</span>';
            })
            ->addColumn('price_range', function ($cluster) {
                return $cluster->formatted_price_range;
            })
            ->addColumn('coordinates', function ($cluster) {
                return $cluster->coordinates ?? 'Not set';
            })
            ->addColumn('is_active_text', function ($cluster) {
                return $cluster->is_active ? 'Yes' : 'No';
            })
            ->addColumn('actions', function ($cluster) {
                return '
                    <div class="dropdown-actions">
                        <button id="dropdownMenuIconButton-' . $cluster->id . '" data-dropdown-toggle="dropdownDots-' . $cluster->id . '" class="text-gray-700 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm p-2 focus:outline-none" type="button" onclick="toggleDropdown(' . $cluster->id . ')">
                            <i class="fas fa-ellipsis"></i>
                        </button>
                        <div id="dropdown-' . $cluster->id . '" class="dropdown-menu hidden">
                            <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton-' . $cluster->id . '">
                                <li>
                                    <a href="' . route('clusters.show', $cluster) . '" class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900">View</a>
                                </li>
                                <li>
                                    <a href="' . route('clusters.edit', $cluster) . '" class="block px-4 py-2 hover:bg-gray-100 hover:text-gray-900">Edit</a>
                                </li>
                            </ul>
                            <div class="py-1 border-t border-gray-200">
                                <form method="POST" action="' . route('clusters.destroy', $cluster) . '" class="inline">
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
            ->rawColumns(['site_plan', 'actions'])
            ->make(true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    protected $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index(): View
    {
        return view('pages.companies.index');
    }

    public function api(Request $request)
    {
        $companies = $this->companyRepository->getAll();

        return DataTables::of($companies)
            ->addColumn('is_active_text', function ($company) {
                return $company->is_active ? 'Yes' : 'No';
            })
            ->addColumn('actions', function ($company) {
                return '
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700 p-1 rounded" onclick="toggleDropdown(' . $company->id . ')">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="dropdown-' . $company->id . '" class="absolute right-0 mt-1 w-32 bg-white border border-gray-200 rounded shadow-lg z-10 hidden">
                            <a href="' . route('companies.edit', $company) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit mr-2 text-blue-500"></i> Edit
                            </a>
                            <form method="POST" action="' . route('companies.destroy', $company) . '" class="inline">
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
        return view('pages.companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:companies',
        ]);

        $this->companyRepository->create($request->all());

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function show($id): View
    {
        $company = $this->companyRepository->findById($id);
        return view('pages.companies.show', compact('company'));
    }

    public function edit($id): View
    {
        $company = $this->companyRepository->findById($id);
        return view('pages.companies.edit', compact('company'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:companies,slug,' . $id,
        ]);

        $company = $this->companyRepository->findById($id);
        $this->companyRepository->update($company, $request->all());

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        $company = $this->companyRepository->findById($id);
        $this->companyRepository->delete($company);

        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\CompanyRepositoryInterface;
use App\Models\Cluster;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $userRepository;
    protected $companyRepository;

    public function __construct(UserRepositoryInterface $userRepository, CompanyRepositoryInterface $companyRepository)
    {
        $this->userRepository = $userRepository;
        $this->companyRepository = $companyRepository;
    }

    public function index(): View
    {
        $totalClusters = Cluster::count();
        $totalUnits = Unit::count();
        $activeClusters = Cluster::where('is_active', true)->count();
        $activeUnits = Unit::where('status', 'available')->count();
        $activeClustersList = Cluster::with('units')->where('is_active', true)->take(5)->get(); // Ambil 5 cluster aktif terbaru dengan units
        $activeUnitsList = Unit::where('status', 'available')->take(10)->get(); // Ambil 10 unit aktif terbaru

        return view('pages.dashboard.index', compact('totalClusters', 'totalUnits', 'activeClusters', 'activeUnits', 'activeClustersList', 'activeUnitsList'));
    }
}

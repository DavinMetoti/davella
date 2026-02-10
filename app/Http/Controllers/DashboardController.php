<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\Cluster;
use App\Models\Unit;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): View
    {
        // Basic stats
        $totalClusters = Cluster::count();
        $totalUnits = Unit::count();
        $activeClusters = Cluster::where('is_active', true)->count();
        $activeUnits = Unit::where('status', 'available')->count();

        // Reservation stats
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();
        $thisMonthReservations = Reservation::whereMonth('created_at', Carbon::now()->month)->count();

        // Revenue stats (from booking fees and dp plans)
        $totalRevenue = Reservation::where('status', 'confirmed')->sum(\DB::raw('booking_fee + COALESCE(dp_plan, 0)'));
        $thisMonthRevenue = Reservation::where('status', 'confirmed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum(\DB::raw('booking_fee + COALESCE(dp_plan, 0)'));

        // Sales performance
        $totalSalesUsers = User::role('sales')->count();
        $topSalesUsers = User::role('sales')
            ->withCount(['reservations' => function ($query) {
                $query->where('status', 'confirmed');
            }])
            ->orderBy('reservations_count', 'desc')
            ->take(5)
            ->get();

        // Recent activities
        $recentReservations = Reservation::with(['unit.cluster', 'sales'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Unit status distribution
        $unitStatusStats = [
            'available' => Unit::where('status', 'available')->count(),
            'reserved' => Unit::where('status', 'reserved')->count(),
            'booked' => Unit::where('status', 'booked')->count(),
        ];

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Reservation::where('status', 'confirmed')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum(\DB::raw('booking_fee + COALESCE(dp_plan, 0)'));
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue
            ];
        }

        $activeClustersList = Cluster::with('units')->where('is_active', true)->take(5)->get();
        $activeUnitsList = Unit::where('status', 'available')->take(10)->get();

        return view('pages.dashboard.index', compact(
            'totalClusters', 'totalUnits', 'activeClusters', 'activeUnits',
            'totalReservations', 'pendingReservations', 'confirmedReservations', 'thisMonthReservations',
            'totalRevenue', 'thisMonthRevenue', 'totalSalesUsers', 'topSalesUsers',
            'recentReservations', 'unitStatusStats', 'monthlyRevenue',
            'activeClustersList', 'activeUnitsList'
        ));
    }

    public function salesReport(Request $request): View
    {
        // Only allow super_admin and owner to access sales report
        if (!auth()->user()->hasRole(['super_admin', 'Owner'])) {
            abort(403, 'Unauthorized access to sales report.');
        }
        // Get all sales users with their performance data
        $salesUsers = User::role('sales')
            ->with(['reservations' => function ($query) {
                $query->select('id', 'sales_id', 'status', 'booking_fee', 'dp_plan', 'created_at')
                      ->orderBy('created_at', 'desc');
            }])
            ->get();

        // Calculate performance metrics for each sales
        $salesPerformance = $salesUsers->map(function ($sales) {
            $reservations = $sales->reservations;
            $totalReservations = $reservations->count();
            $confirmedReservations = $reservations->where('status', 'confirmed')->count();
            $pendingReservations = $reservations->where('status', 'pending')->count();
            $cancelledReservations = $reservations->where('status', 'cancelled')->count();

            // Revenue calculation
            $confirmedRes = $reservations->where('status', 'confirmed');
            $totalRevenue = $confirmedRes->sum(function ($reservation) {
                return $reservation->booking_fee + ($reservation->dp_plan ?? 0);
            });

            // Monthly performance for the last 6 months
            $monthlyData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthReservations = $reservations->where('created_at', '>=', $date->startOfMonth())
                                                 ->where('created_at', '<=', $date->endOfMonth());
                $monthConfirmed = $monthReservations->where('status', 'confirmed');
                $monthRevenue = $monthConfirmed->sum(function ($reservation) {
                    return $reservation->booking_fee + ($reservation->dp_plan ?? 0);
                });

                $monthlyData[] = [
                    'month' => $date->format('M Y'),
                    'reservations' => $monthReservations->count(),
                    'confirmed' => $monthConfirmed->count(),
                    'revenue' => $monthRevenue
                ];
            }

            return [
                'id' => $sales->id,
                'name' => $sales->name,
                'email' => $sales->email,
                'total_reservations' => $totalReservations,
                'confirmed_reservations' => $confirmedReservations,
                'pending_reservations' => $pendingReservations,
                'cancelled_reservations' => $cancelledReservations,
                'total_revenue' => $totalRevenue,
                'conversion_rate' => $totalReservations > 0 ? round(($confirmedReservations / $totalReservations) * 100, 1) : 0,
                'monthly_data' => $monthlyData,
                'last_reservation' => $reservations->first()?->created_at
            ];
        })->sortByDesc('total_revenue');

        // Overall statistics
        $totalSales = $salesUsers->count();
        $totalAllReservations = Reservation::whereNotNull('sales_id')->count();
        $totalConfirmedReservations = Reservation::where('status', 'confirmed')->whereNotNull('sales_id')->count();
        $totalRevenueAll = Reservation::where('status', 'confirmed')->whereNotNull('sales_id')
            ->sum(\DB::raw('booking_fee + COALESCE(dp_plan, 0)'));

        // Top performers
        $topPerformers = $salesPerformance->take(5);

        return view('pages.dashboard.sales-report', compact(
            'salesPerformance', 'totalSales', 'totalAllReservations',
            'totalConfirmedReservations', 'totalRevenueAll', 'topPerformers'
        ));
    }
}

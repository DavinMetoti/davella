<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('pages.reservations.index');
    }

    /**
     * API endpoint for DataTables.
     */
    public function api(Request $request)
    {
        $reservations = Reservation::with(['unit.cluster', 'sales', 'creator']);

        // Filter reservations based on user role
        $user = Auth::user();
        if ($user->hasRole('sales')) {
            // Sales users can only see their own reservations
            $reservations->where('sales_id', $user->id);
        }
        // Admin, Owner, and super_admin can see all reservations (no additional filter needed)

        return DataTables::of($reservations)
            ->addColumn('reservation_code', function ($reservation) {
                return '<span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">' . $reservation->reservation_code . '</span>';
            })
            ->addColumn('customer_info', function ($reservation) {
                return '<div class="text-sm">
                    <div class="font-medium">' . $reservation->customer_name . '</div>
                    <div class="text-gray-500">' . $reservation->customer_phone . '</div>
                </div>';
            })
            ->addColumn('unit_info', function ($reservation) {
                return '<div class="text-sm">
                    <div class="font-medium">' . $reservation->unit->name . '</div>
                    <div class="text-gray-500">' . $reservation->unit->cluster->name . '</div>
                </div>';
            })
            ->addColumn('sales_info', function ($reservation) {
                return '<span class="text-sm">' . $reservation->sales->name . '</span>';
            })
            ->addColumn('price_formatted', function ($reservation) {
                return '<span class="font-medium text-green-600">Rp ' . number_format($reservation->price_snapshot, 0, ',', '.') . '</span>';
            })
            ->addColumn('booking_fee_formatted', function ($reservation) {
                return $reservation->booking_fee ? '<span class="text-sm text-blue-600">Rp ' . number_format($reservation->booking_fee, 0, ',', '.') . '</span>' : '<span class="text-gray-400">-</span>';
            })
            ->addColumn('dp_plan_formatted', function ($reservation) {
                return $reservation->dp_plan ? '<span class="text-sm text-purple-600">Rp ' . number_format($reservation->dp_plan, 0, ',', '.') . '</span>' : '<span class="text-gray-400">-</span>';
            })
            ->addColumn('status_badge', function ($reservation) {
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'confirmed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'expired' => 'bg-gray-100 text-gray-800',
                ];
                $colorClass = $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800';
                return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $colorClass . '">' . ucfirst($reservation->status) . '</span>';
            })
            ->addColumn('reservation_date_formatted', function ($reservation) {
                return '<span class="text-sm">' . $reservation->reservation_date->format('d M Y H:i') . '</span>';
            })
            ->addColumn('expired_at_formatted', function ($reservation) {
                $expiredClass = $reservation->expired_at->isPast() ? 'text-red-600 font-medium' : 'text-gray-600';
                return '<span class="text-sm ' . $expiredClass . '">' . $reservation->expired_at->format('d M Y H:i') . '</span>';
            })
            ->addColumn('actions', function ($reservation) {
                return '
                    <div class="dropdown-actions relative">
                        <button class="text-gray-500 hover:text-gray-700 p-1 rounded" onclick="toggleDropdown(' . $reservation->id . ')">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div id="dropdown-' . $reservation->id . '" class="absolute right-0 mt-1 w-40 bg-white border border-gray-200 rounded shadow-lg z-50 hidden">
                            <a href="' . route('reservations.show', $reservation) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-eye mr-2 text-blue-500"></i> View
                            </a>
                            <a href="' . route('reservations.edit', $reservation) . '" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit mr-2 text-green-500"></i> Edit
                            </a>
                            <form method="POST" action="' . route('reservations.destroy', $reservation) . '" class="inline">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-trash-alt mr-2 text-red-500"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                ';
            })
            ->rawColumns(['reservation_code', 'customer_info', 'unit_info', 'sales_info', 'price_formatted', 'booking_fee_formatted', 'dp_plan_formatted', 'status_badge', 'reservation_date_formatted', 'expired_at_formatted', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $units = Unit::available()->with('cluster')->get();
        $salesUsers = User::role('sales')->get();
        $selectedUnit = null;
        $selectedSales = null;
        $isSales = false;

        if ($request->has('unit_id')) {
            $selectedUnit = Unit::find($request->unit_id);
        }

        // Check if current user is sales
        if (Auth::user()->hasRole('sales')) {
            $selectedSales = Auth::user();
            $isSales = true;
        }

        return view('pages.reservations.create', compact('units', 'salesUsers', 'selectedUnit', 'selectedSales', 'isSales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Clean currency fields before validation
        $request->merge([
            'booking_fee' => $this->cleanCurrencyValue($request->booking_fee),
            'dp_plan' => $this->cleanCurrencyValue($request->dp_plan),
        ]);

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'ktp_number' => 'required|string|max:30',
            'sales_id' => 'required|exists:users,id',
            'payment_method' => 'nullable|string|max:20',
            'booking_fee' => 'nullable|numeric|min:0',
            'dp_plan' => 'nullable|numeric|min:0',
            'reservation_date' => 'required|date',
            'expired_at' => 'required|date|after:reservation_date',
        ]);

        $unit = Unit::findOrFail($request->unit_id);

        // Validate that booking fee and DP plan don't exceed unit price
        if ($request->booking_fee && $request->booking_fee > $unit->price) {
            return back()->withErrors(['booking_fee' => 'Booking fee cannot exceed unit price (Rp ' . number_format($unit->price, 0, ',', '.') . ')'])->withInput();
        }

        if ($request->dp_plan && $request->dp_plan > $unit->price) {
            return back()->withErrors(['dp_plan' => 'DP plan cannot exceed unit price (Rp ' . number_format($unit->price, 0, ',', '.') . ')'])->withInput();
        }

        // Generate reservation code - find the highest existing number
        $lastCode = Reservation::orderByRaw('CAST(SUBSTRING(reservation_code, 4) AS UNSIGNED) DESC')->value('reservation_code');
        $nextNumber = $lastCode ? intval(substr($lastCode, 3)) + 1 : 1;
        $reservationCode = 'RSV' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Reservation::create([
            'reservation_code' => $reservationCode,
            'reservation_date' => $request->reservation_date,
            'expired_at' => $request->expired_at,
            'unit_id' => $request->unit_id,
            'price_snapshot' => $unit->price,
            'promo_snapshot' => null, // Can be extended later
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'ktp_number' => $request->ktp_number,
            'sales_id' => $request->sales_id,
            'payment_method' => $request->payment_method,
            'booking_fee' => $request->booking_fee,
            'dp_plan' => $request->dp_plan,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);

        // Update unit status to reserved
        $unit->update(['status' => 'reserved']);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): View
    {
        // Check if user can access this reservation
        if (!$this->canAccessReservation($reservation)) {
            abort(403, 'Unauthorized access to reservation.');
        }

        $reservation->load(['unit.cluster', 'sales', 'creator']);
        return view('pages.reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation): View
    {
        // Check if user can access this reservation
        if (!$this->canAccessReservation($reservation)) {
            abort(403, 'Unauthorized access to reservation.');
        }

        // Prevent editing confirmed reservations
        if ($reservation->status === 'confirmed') {
            return redirect()->route('reservations.index')
                ->with('error', 'Confirmed reservations cannot be edited.');
        }

        $units = Unit::where(function ($query) use ($reservation) {
            $query->available()->orWhere('id', $reservation->unit_id);
        })->with('cluster')->get();
        $salesUsers = User::role('sales')->get();

        return view('pages.reservations.edit', compact('reservation', 'units', 'salesUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        // Check if user can access this reservation
        if (!$this->canAccessReservation($reservation)) {
            abort(403, 'Unauthorized access to reservation.');
        }

        // Prevent updating confirmed reservations
        if ($reservation->status === 'confirmed') {
            return redirect()->route('reservations.index')
                ->with('error', 'Confirmed reservations cannot be modified.');
        }

        // Clean currency fields before validation
        $request->merge([
            'booking_fee' => $this->cleanCurrencyValue($request->booking_fee),
            'dp_plan' => $this->cleanCurrencyValue($request->dp_plan),
        ]);

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'ktp_number' => 'required|string|max:30',
            'sales_id' => 'required|exists:users,id',
            'payment_method' => 'nullable|string|max:20',
            'booking_fee' => 'nullable|numeric|min:0',
            'dp_plan' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:pending,confirmed,cancelled,expired',
            'reservation_date' => 'required|date',
            'expired_at' => 'required|date|after:reservation_date',
        ]);

        // Check if unit has changed
        $oldUnitId = $reservation->unit_id;
        $newUnitId = $request->unit_id;

        // Get the unit to validate prices
        $unit = Unit::findOrFail($newUnitId);

        // Validate that booking fee and DP plan don't exceed unit price
        if ($request->booking_fee && $request->booking_fee > $unit->price) {
            return back()->withErrors(['booking_fee' => 'Booking fee cannot exceed unit price (Rp ' . number_format($unit->price, 0, ',', '.') . ')'])->withInput();
        }

        if ($request->dp_plan && $request->dp_plan > $unit->price) {
            return back()->withErrors(['dp_plan' => 'DP plan cannot exceed unit price (Rp ' . number_format($unit->price, 0, ',', '.') . ')'])->withInput();
        }

        $reservation->update([
            'reservation_date' => $request->reservation_date,
            'expired_at' => $request->expired_at,
            'unit_id' => $request->unit_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'ktp_number' => $request->ktp_number,
            'sales_id' => $request->sales_id,
            'payment_method' => $request->payment_method,
            'booking_fee' => $request->booking_fee,
            'dp_plan' => $request->dp_plan,
            'status' => $request->status,
        ]);

        // Update unit statuses if unit changed
        if ($oldUnitId !== $newUnitId) {
            // Set old unit back to available
            Unit::where('id', $oldUnitId)->update(['status' => 'available']);
            // Set new unit to reserved
            Unit::where('id', $newUnitId)->update(['status' => 'reserved']);
        }

        // Update unit status based on reservation status
        $finalStatus = $request->status;
        if (in_array($finalStatus, ['cancelled', 'expired'])) {
            // If reservation is cancelled or expired, set unit back to available
            Unit::where('id', $newUnitId)->update(['status' => 'available']);
        } elseif ($finalStatus === 'confirmed') {
            // If reservation is confirmed, set unit to booked (fully committed)
            Unit::where('id', $newUnitId)->update(['status' => 'booked']);
        }
        // For 'pending' status, unit remains reserved

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        // Check if user can access this reservation
        if (!$this->canAccessReservation($reservation)) {
            abort(403, 'Unauthorized access to reservation.');
        }

        // Prevent deleting confirmed reservations
        if ($reservation->status === 'confirmed') {
            return redirect()->route('reservations.index')
                ->with('error', 'Confirmed reservations cannot be deleted.');
        }

        // Set unit back to available when reservation is deleted
        Unit::where('id', $reservation->unit_id)->update(['status' => 'available']);

        $reservation->delete();

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }

    /**
     * Clean currency value by removing formatting and converting to numeric
     */
    public function cleanCurrencyValue($value): ?float
    {
        if (empty($value)) {
            return null;
        }

        // Convert to string if it's not already
        $value = (string) $value;

        // Remove currency symbols and spaces
        $cleaned = preg_replace('/[^\d.,-]/', '', $value);

        // Handle Indonesian number format: dots as thousands separators, comma as decimal
        // First, check if there's a comma (decimal separator)
        if (strpos($cleaned, ',') !== false) {
            // Split by comma, take the last part as decimal
            $parts = explode(',', $cleaned);
            $decimal = array_pop($parts);
            $integer = implode('', $parts);

            // Remove dots from integer part (thousands separators)
            $integer = str_replace('.', '', $integer);

            $cleaned = $integer . '.' . $decimal;
        } else {
            // No decimal, just remove dots
            $cleaned = str_replace('.', '', $cleaned);
        }

        return (float) $cleaned;
    }

    /**
     * Check if the current user can access the given reservation
     */
    private function canAccessReservation(Reservation $reservation): bool
    {
        $user = Auth::user();

        // Admin, Owner, and super_admin can access all reservations
        if ($user->hasRole(['super_admin', 'Owner'])) {
            return true;
        }

        // Sales users can only access their own reservations
        if ($user->hasRole('sales')) {
            return $reservation->sales_id === $user->id;
        }

        // Default deny for other roles
        return false;
    }
}

@extends('pages.layout')

@section('main')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Reservation Details</h1>
        <p class="text-gray-600 mt-1">Reservation Code: <span class="font-mono font-medium">{{ $reservation->reservation_code }}</span></p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('reservations.edit', $reservation) }}" class="btn-secondary hover:bg-opacity-90 px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="{{ route('reservations.index') }}" class="btn-primary hover:bg-opacity-90 px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Reservations
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Reservation Status -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Reservation Status</h2>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <div class="mt-1">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'expired' => 'bg-gray-100 text-gray-800',
                            ];
                            $colorClass = $statusColors[$reservation->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $colorClass }}">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Reservation Date</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->reservation_date->format('d M Y H:i') }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Expired At</label>
                    <p class="mt-1 {{ $reservation->expired_at->isPast() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                        {{ $reservation->expired_at->format('d M Y H:i') }}
                        @if($reservation->expired_at->isPast())
                            <span class="text-xs">(Expired)</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Customer Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Customer Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Customer Name</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $reservation->customer_name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Phone Number</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->customer_phone }}</p>
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500">KTP Number</label>
                    <p class="mt-1 text-gray-900 font-mono">{{ $reservation->ktp_number }}</p>
                </div>
            </div>
        </div>

        <!-- Unit Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Unit Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Unit Name</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $reservation->unit->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Cluster</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->unit->cluster->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">House Type</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->unit->house_type }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Land Area</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->unit->formatted_land_area }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Building Area</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->unit->formatted_building_area }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Price</label>
                    <p class="mt-1 text-gray-900 font-medium text-green-600">Rp {{ number_format($reservation->price_snapshot, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Payment Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Payment Method</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->payment_method ? ucfirst(str_replace('_', ' ', $reservation->payment_method)) : '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Booking Fee</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->booking_fee ? 'Rp ' . number_format($reservation->booking_fee, 0, ',', '.') : '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">DP Plan</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->dp_plan ? 'Rp ' . number_format($reservation->dp_plan, 0, ',', '.') : '-' }}</p>
                </div>
            </div>

            @if($reservation->promo_snapshot)
                <div class="mt-6 pt-6 border-t">
                    <label class="text-sm font-medium text-gray-500">Promo Applied</label>
                    <div class="mt-1">
                        @foreach($reservation->promo_snapshot as $key => $value)
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 text-xs font-medium rounded-full mr-2">
                                {{ ucfirst($key) }}: {{ $value }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- KPR Information (if applicable) -->
        @if($reservation->payment_plan === 'kpr')
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">KPR Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Interest Type</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->interest_type ? ucfirst($reservation->interest_type) : '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Loan Term</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->loan_term ? $reservation->loan_term . ' Years' : '-' }}</p>
                </div>

                @if($reservation->interest_type === 'flat')
                <div>
                    <label class="text-sm font-medium text-gray-500">Flat Interest Rate</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->flat_rate ? $reservation->flat_rate . '%' : '-' }}</p>
                </div>
                @endif

                @if($reservation->interest_type === 'tiered' && $reservation->tiered_rates)
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-500">Tiered Interest Rates</label>
                    <div class="mt-1 space-y-2">
                        @foreach($reservation->tiered_rates as $tier)
                        <div class="flex items-center space-x-4 p-2 bg-gray-50 rounded">
                            <span class="text-sm">Rate: {{ $tier['rate'] }}%</span>
                            <span class="text-sm">Years: {{ $tier['years'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label class="text-sm font-medium text-gray-500">Loan Amount</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $reservation->loan_amount ? 'Rp ' . number_format($reservation->loan_amount, 0, ',', '.') : '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Monthly Payment</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $reservation->monthly_payment ? 'Rp ' . number_format($reservation->monthly_payment, 0, ',', '.') : '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Total Payment</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $reservation->total_payment ? 'Rp ' . number_format($reservation->total_payment, 0, ',', '.') : '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Total Interest</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $reservation->total_interest ? 'Rp ' . number_format($reservation->total_interest, 0, ',', '.') : '-' }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Remaining Amount Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Payment Summary</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Payment Plan</label>
                    <p class="mt-1 text-gray-900 font-medium">{{ $reservation->payment_plan ? ucfirst($reservation->payment_plan) : '-' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Remaining Amount to Pay</label>
                    <p class="mt-1 text-gray-900 font-medium text-red-600">{{ $reservation->remaining_amount ? 'Rp ' . number_format($reservation->remaining_amount, 0, ',', '.') : '-' }}</p>
                </div>
            </div>

            @if($reservation->payment_plan === 'kpr')
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Note:</strong> The remaining amount represents the loan amount that will be financed through KPR.
                    Monthly payments will be calculated based on the KPR terms above.
                </p>
            </div>
            @elseif($reservation->payment_plan === 'lunas')
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <strong>Note:</strong> The remaining amount must be paid in full to complete the purchase.
                </p>
            </div>
            @endif
        </div>

        <!-- Sales & Creator Information -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Sales & System Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Sales Person</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->sales->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Created By</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->creator ? $reservation->creator->name : 'System' }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->created_at->format('d M Y H:i') }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="mt-1 text-gray-900">{{ $reservation->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
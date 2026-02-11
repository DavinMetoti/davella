@extends('pages.layout')

@section('main')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Customer Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('customers.edit', $customer) }}"
               class="px-4 py-2 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Customer
            </a>
            <a href="{{ route('customers.index') }}"
               class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-200 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Customer Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Full Name</label>
                    <p class="text-lg font-medium text-gray-900">{{ $customer->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Phone Number</label>
                    <p class="text-lg font-medium text-gray-900">{{ $customer->phone ?: 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Email Address</label>
                    <p class="text-lg font-medium text-gray-900">{{ $customer->email ?: 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">KTP Number</label>
                    <p class="text-lg font-medium text-gray-900">{{ $customer->ktp_number ?: 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Birth Date</label>
                    <p class="text-lg font-medium text-gray-900">{{ $customer->birth_date ? $customer->birth_date->format('d M Y') : 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Gender</label>
                    <p class="text-lg font-medium text-gray-900">{{ $customer->gender ? ucfirst($customer->gender) : 'Not specified' }}</p>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Address Information</h2>
            <div>
                <label class="block text-sm font-medium text-gray-600">Address</label>
                <p class="text-lg text-gray-900 mt-1">{{ $customer->address ?: 'Not provided' }}</p>
            </div>
        </div>

        <!-- Notes -->
        @if($customer->notes)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Notes</h2>
            <p class="text-gray-700">{{ $customer->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Reservations Summary -->
    <div class="space-y-6">
        <!-- Reservation Count -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Reservations</h2>
            <div class="text-center">
                <div class="text-3xl font-bold text-[#2FA769]">{{ $customer->reservations->count() }}</div>
                <p class="text-gray-600">Total Reservations</p>
            </div>
        </div>

        <!-- Recent Reservations -->
        @if($customer->reservations->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Reservations</h2>
            <div class="space-y-3">
                @foreach($customer->reservations->take(5) as $reservation)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">{{ $reservation->reservation_code }}</p>
                        <p class="text-sm text-gray-600">{{ $reservation->unit->name ?? 'N/A' }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full
                        @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                        @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
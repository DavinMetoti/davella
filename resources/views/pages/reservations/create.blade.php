@extends('pages.layout')

@section('main')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Create New Reservation</h1>
        <p class="text-gray-600 mt-1">Add a new property reservation</p>
    </div>
    <a href="{{ route('reservations.index') }}" class="btn-secondary hover:bg-opacity-90 px-4 py-2 rounded-lg transition duration-200">
        <i class="fas fa-arrow-left mr-2"></i>Back to Reservations
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('reservations.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Unit Selection -->
            <div>
                <label for="unit_id" class="block text-sm font-semibold text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                <select name="unit_id" id="unit_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" required>
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ (old('unit_id') == $unit->id || (isset($selectedUnit) && $selectedUnit->id == $unit->id)) ? 'selected' : '' }}>
                            {{ $unit->name }} - {{ $unit->cluster->name }} ({{ $unit->house_type }})
                        </option>
                    @endforeach
                </select>
                @error('unit_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Sales Selection -->
            <div>
                <label for="sales_id" class="block text-sm font-semibold text-gray-700 mb-2">Sales Person <span class="text-red-500">*</span></label>
                @if(isset($isSales) && $isSales)
                    <!-- For sales users: show readonly field and hidden input -->
                    <input type="text" value="{{ $selectedSales->name }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 cursor-not-allowed" readonly>
                    <input type="hidden" name="sales_id" value="{{ $selectedSales->id }}">
                    <p class="text-xs text-gray-500 mt-1">Sales person is automatically set to your account</p>
                @else
                    <!-- For admin/owner: show dropdown -->
                    <select name="sales_id" id="sales_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" required>
                        <option value="">Select Sales Person</option>
                        @foreach($salesUsers as $sales)
                            <option value="{{ $sales->id }}" {{ (old('sales_id') == $sales->id || (isset($selectedSales) && $selectedSales->id == $sales->id)) ? 'selected' : '' }}>
                                {{ $sales->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
                @error('sales_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Customer Information -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">Customer Name <span class="text-red-500">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           required>
                    @error('customer_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           placeholder="+62 812-3456-7890" required>
                    @error('customer_phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="ktp_number" class="block text-sm font-semibold text-gray-700 mb-2">KTP Number <span class="text-red-500">*</span></label>
                <input type="text" name="ktp_number" id="ktp_number" value="{{ old('ktp_number') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                       placeholder="Enter 16-digit KTP number" required>
                @error('ktp_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Reservation Details -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservation Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="reservation_date" class="block text-sm font-semibold text-gray-700 mb-2">Reservation Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="reservation_date" id="reservation_date" value="{{ old('reservation_date', now()->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           required>
                    @error('reservation_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="expired_at" class="block text-sm font-semibold text-gray-700 mb-2">Expired At <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="expired_at" id="expired_at" value="{{ old('expired_at', now()->addDays(7)->format('Y-m-d\TH:i')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           required>
                    @error('expired_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                        <option value="">Select Payment Method</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    </select>
                    @error('payment_method') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="booking_fee" class="block text-sm font-semibold text-gray-700 mb-2">Booking Fee</label>
                    <input type="text" name="booking_fee" id="booking_fee" value="{{ old('booking_fee') }}"
                           class="currency-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           placeholder="0">
                    @error('booking_fee') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="dp_plan" class="block text-sm font-semibold text-gray-700 mb-2">DP Plan</label>
                    <input type="text" name="dp_plan" id="dp_plan" value="{{ old('dp_plan') }}"
                           class="currency-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           placeholder="0">
                    @error('dp_plan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('reservations.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-[#2FA769] text-white rounded-lg hover:bg-[#256f4a] transition duration-200 font-medium shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Reservation
            </button>
        </div>
    </form>
</div>

<script>
// Currency formatting for payment inputs
document.addEventListener('DOMContentLoaded', function() {
    const currencyInputs = document.querySelectorAll('.currency-input');

    currencyInputs.forEach(input => {
        // Format initial value if exists
        if (input.value) {
            let value = input.value.replace(/[^\d]/g, '');
            input.dataset.rawValue = value; // Store raw value
            input.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
        }

        input.addEventListener('focus', function() {
            // Remove formatting for editing
            this.dataset.rawValue = this.value.replace(/[^\d]/g, '');
            this.value = this.dataset.rawValue;
        });

        input.addEventListener('input', function() {
            // Real-time formatting and store raw value
            let value = this.value.replace(/[^\d]/g, '');
            this.dataset.rawValue = value;
            if (value) {
                this.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
        });

        input.addEventListener('blur', function() {
            // Ensure formatting on blur
            let value = this.dataset.rawValue || '';
            if (value) {
                this.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            } else {
                this.value = '';
            }
        });
    });

    // Strip formatting before form submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        currencyInputs.forEach(input => {
            const rawValue = input.dataset.rawValue || '';
            input.value = rawValue; // Send raw numeric value to server
        });
    });
});
</script>
@endsection
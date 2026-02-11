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

            <!-- Customer Selection -->
            <div class="mb-6">
                <label for="customer_select" class="block text-sm font-semibold text-gray-700 mb-2">Select Existing Customer (Optional)</label>
                <select name="customer_id" id="customer_select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                    <option value="">Choose existing customer or fill manually below</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}"
                                data-name="{{ $customer->name }}"
                                data-phone="{{ $customer->phone }}"
                                data-ktp="{{ $customer->ktp_number }}">
                            {{ $customer->name }} - {{ $customer->phone ?: 'No phone' }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Selecting a customer will auto-fill the fields below</p>
            </div>

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                    <label for="payment_plan" class="block text-sm font-semibold text-gray-700 mb-2">Payment Plan <span class="text-red-500">*</span></label>
                    <select name="payment_plan" id="payment_plan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white" required>
                        <option value="">Select Payment Plan</option>
                        <option value="lunas" {{ old('payment_plan') == 'lunas' ? 'selected' : '' }}>Lunas (Full Payment)</option>
                        <option value="kpr" {{ old('payment_plan') == 'kpr' ? 'selected' : '' }}>KPR (Mortgage)</option>
                    </select>
                    @error('payment_plan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="booking_fee" class="block text-sm font-semibold text-gray-700 mb-2">Booking Fee</label>
                    <input type="text" name="booking_fee" id="booking_fee" value="{{ old('booking_fee') }}"
                           class="currency-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           placeholder="0">
                    @error('booking_fee') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="dp_plan" class="block text-sm font-semibold text-gray-700 mb-2">DP Plan (%)</label>
                    <select name="dp_plan_percentage" id="dp_plan_percentage" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                        <option value="">Select DP Percentage</option>
                        @for($i = 5; $i <= 90; $i += 5)
                            <option value="{{ $i }}">{{ $i }}%</option>
                        @endfor
                    </select>
                    <input type="hidden" name="dp_plan" id="dp_plan_hidden" value="{{ old('dp_plan') }}">
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-700">Calculated DP Amount:</span>
                            <span class="text-lg font-bold text-blue-800" id="dp_amount_display">Rp 0</span>
                        </div>
                        <p class="text-xs text-blue-600 mt-1">Amount will be calculated based on selected unit price × percentage</p>
                    </div>
                    @error('dp_plan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- KPR Information (shown only when KPR is selected) -->
        <div id="kpr_section" class="border-t pt-6" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">KPR Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="interest_type" class="block text-sm font-semibold text-gray-700 mb-2">Interest Type <span class="text-red-500">*</span></label>
                    <select name="interest_type" id="interest_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                        <option value="">Select Interest Type</option>
                        <option value="flat" {{ old('interest_type') == 'flat' ? 'selected' : '' }}>Flat Rate</option>
                        <option value="tiered" {{ old('interest_type') == 'tiered' ? 'selected' : '' }}>Tiered Rate</option>
                    </select>
                    @error('interest_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="loan_term" class="block text-sm font-semibold text-gray-700 mb-2">Loan Term (Years) <span class="text-red-500">*</span></label>
                    <select name="loan_term" id="loan_term" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white">
                        <option value="">Select Loan Term</option>
                        @for($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}" {{ old('loan_term') == $i ? 'selected' : '' }}>{{ $i }} Year{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    @error('loan_term') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Flat Rate Section -->
            <div id="flat_rate_section" style="display: none;">
                <div class="mb-6">
                    <label for="flat_rate" class="block text-sm font-semibold text-gray-700 mb-2">Flat Interest Rate (%) <span class="text-red-500">*</span></label>
                    <input type="number" name="flat_rate" id="flat_rate" value="{{ old('flat_rate') }}" step="0.01" min="0" max="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769] transition duration-200 bg-gray-50 focus:bg-white"
                           placeholder="e.g., 8.5">
                    @error('flat_rate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tiered Rate Section -->
            <div id="tiered_rate_section" style="display: none;">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tiered Interest Rates <span class="text-red-500">*</span></label>
                    <div id="tiered_rates_container">
                        <!-- Tiered rates will be added here dynamically -->
                    </div>
                    <button type="button" id="add_tier_button" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Tier
                    </button>
                    @error('tiered_rates') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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

    // DP Plan calculation functionality
    const unitSelect = document.getElementById('unit_id');
    const dpPlanPercentageSelect = document.getElementById('dp_plan_percentage');
    const dpPlanHiddenInput = document.getElementById('dp_plan_hidden');
    const dpAmountDisplay = document.getElementById('dp_amount_display');

    // Store unit prices (we'll need to get this from the selected option)
    const unitPrices = {};
    @foreach($units as $unit)
        unitPrices[{{ $unit->id }}] = {{ $unit->price }};
    @endforeach

    function calculateDPAmount() {
        const selectedUnitId = unitSelect.value;
        const selectedPercentage = dpPlanPercentageSelect.value;

        if (selectedUnitId && selectedPercentage && unitPrices[selectedUnitId]) {
            const unitPrice = unitPrices[selectedUnitId];
            const percentage = parseInt(selectedPercentage);
            const dpAmount = Math.round(unitPrice * (percentage / 100));

            dpPlanHiddenInput.value = dpAmount;
            dpAmountDisplay.textContent = 'Rp ' + dpAmount.toLocaleString('id-ID');
        } else {
            dpPlanHiddenInput.value = '';
            dpAmountDisplay.textContent = 'Rp 0';
        }
    }

    unitSelect.addEventListener('change', calculateDPAmount);
    dpPlanPercentageSelect.addEventListener('change', calculateDPAmount);

    // Customer selection auto-fill functionality
    const customerSelect = document.getElementById('customer_select');
    const customerNameInput = document.getElementById('customer_name');
    const customerPhoneInput = document.getElementById('customer_phone');
    const ktpNumberInput = document.getElementById('ktp_number');

    customerSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value === '') {
            // Clear fields and enable all inputs if "Choose existing customer" is selected
            customerNameInput.value = '';
            customerPhoneInput.value = '';
            ktpNumberInput.value = '';
            customerNameInput.disabled = false;
            customerPhoneInput.disabled = false;
            ktpNumberInput.disabled = false;
            customerNameInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            customerPhoneInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            ktpNumberInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            customerNameInput.classList.add('bg-gray-50', 'focus:bg-white');
            customerPhoneInput.classList.add('bg-gray-50', 'focus:bg-white');
            ktpNumberInput.classList.add('bg-gray-50', 'focus:bg-white');
        } else {
            // Auto-fill fields with selected customer data and disable all inputs
            customerNameInput.value = selectedOption.getAttribute('data-name') || '';
            customerPhoneInput.value = selectedOption.getAttribute('data-phone') || '';
            ktpNumberInput.value = selectedOption.getAttribute('data-ktp') || '';
            customerNameInput.disabled = true;
            customerPhoneInput.disabled = true;
            ktpNumberInput.disabled = true;
            customerNameInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            customerPhoneInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            ktpNumberInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            customerNameInput.classList.remove('bg-gray-50', 'focus:bg-white');
            customerPhoneInput.classList.remove('bg-gray-50', 'focus:bg-white');
            ktpNumberInput.classList.remove('bg-gray-50', 'focus:bg-white');
        }
    });
});
</script>

<script>
// KPR Section toggle and interest type handling
document.addEventListener('DOMContentLoaded', function() {
    const paymentPlanSelect = document.getElementById('payment_plan');
    const kprSection = document.getElementById('kpr_section');
    const interestTypeSelect = document.getElementById('interest_type');
    const flatRateSection = document.getElementById('flat_rate_section');
    const tieredRateSection = document.getElementById('tiered_rate_section');
    const addTierButton = document.getElementById('add_tier_button');
    const tieredRatesContainer = document.getElementById('tiered_rates_container');

    // Toggle KPR section based on payment plan
    paymentPlanSelect.addEventListener('change', function() {
        if (this.value === 'kpr') {
            kprSection.style.display = 'block';
            // Make KPR fields required
            document.getElementById('interest_type').setAttribute('required', 'required');
            document.getElementById('loan_term').setAttribute('required', 'required');
        } else {
            kprSection.style.display = 'none';
            // Remove required from KPR fields
            document.getElementById('interest_type').removeAttribute('required');
            document.getElementById('loan_term').removeAttribute('required');
            document.getElementById('flat_rate').removeAttribute('required');
            // Hide interest type sections
            flatRateSection.style.display = 'none';
            tieredRateSection.style.display = 'none';
        }
    });

    // Toggle interest type sections
    interestTypeSelect.addEventListener('change', function() {
        if (this.value === 'flat') {
            flatRateSection.style.display = 'block';
            tieredRateSection.style.display = 'none';
            document.getElementById('flat_rate').setAttribute('required', 'required');
            // Clear tiered rates
            tieredRatesContainer.innerHTML = '';
        } else if (this.value === 'tiered') {
            flatRateSection.style.display = 'none';
            tieredRateSection.style.display = 'block';
            document.getElementById('flat_rate').removeAttribute('required');
            // Add initial tier if none exist
            if (tieredRatesContainer.children.length === 0) {
                addTier();
            }
        } else {
            flatRateSection.style.display = 'none';
            tieredRateSection.style.display = 'none';
            document.getElementById('flat_rate').removeAttribute('required');
        }
    });

    // Add tier functionality
    addTierButton.addEventListener('click', addTier);

    function addTier(rate = '', years = '') {
        const tierIndex = tieredRatesContainer.children.length;
        const tierDiv = document.createElement('div');
        tierDiv.className = 'flex items-center space-x-4 mb-3 p-3 bg-gray-50 rounded-lg';
        tierDiv.innerHTML = `
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rate (%)</label>
                <input type="number" name="tiered_rates[${tierIndex}][rate]" value="${rate}" step="0.01" min="0" max="100"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769]"
                       placeholder="e.g., 7.5" required>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Years</label>
                <input type="number" name="tiered_rates[${tierIndex}][years]" value="${years}" min="1" max="30"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FA769] focus:border-[#2FA769]"
                       placeholder="e.g., 5" required>
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-tier px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        tieredRatesContainer.appendChild(tierDiv);

        // Add remove functionality
        tierDiv.querySelector('.remove-tier').addEventListener('click', function() {
            tierDiv.remove();
            updateTierIndices();
        });
    }

    function updateTierIndices() {
        const tiers = tieredRatesContainer.children;
        for (let i = 0; i < tiers.length; i++) {
            const rateInput = tiers[i].querySelector('input[name*="[rate]"]');
            const yearsInput = tiers[i].querySelector('input[name*="[years]"]');
            rateInput.name = `tiered_rates[${i}][rate]`;
            yearsInput.name = `tiered_rates[${i}][years]`;
        }
    }

    // Load existing tiered rates if any (for edit mode)
    @if(old('tiered_rates'))
        @foreach(old('tiered_rates') as $index => $tier)
            addTier('{{ $tier['rate'] }}', '{{ $tier['years'] }}');
        @endforeach
    @endif

    // Trigger change event on page load to show/hide sections based on existing values
    paymentPlanSelect.dispatchEvent(new Event('change'));
    interestTypeSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection
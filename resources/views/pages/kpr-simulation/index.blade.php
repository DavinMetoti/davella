@extends('pages.layout')

@section('main')
<style>
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #333;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 0 2px rgba(0,0,0,0.3);
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #333;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 0 2px rgba(0,0,0,0.3);
}
</style>
<div class="">
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-300">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Simulasi KPR
            </h1>
        </div>

        <!-- Main Layout: Form and Results Side by Side -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Form Section -->
            <div class="flex-1">
                <form id="kpr-form" class="space-y-6">
            @csrf

            <!-- Unit Selection -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Pilihan Properti</h3>
                <div class="space-y-4">
                    <div>
                        <label for="cluster_id" class="block text-sm font-semibold text-gray-700 mb-2">Cluster</label>
                        <select id="cluster_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800">
                            <option value="">Pilih Cluster</option>
                            @foreach($clusters as $cluster)
                                <option value="{{ $cluster->id }}">{{ $cluster->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="unit_id" class="block text-sm font-semibold text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                        <select name="unit_id" id="unit_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800" required>
                            <option value="">Pilih Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" data-price="{{ $unit->price }}" data-cluster="{{ $unit->cluster_id }}">
                                    {{ $unit->unit_number }} - {{ $unit->name }} (Rp {{ number_format($unit->price, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <div class="bg-gray-100 p-3 rounded-lg border">
                        <p class="text-sm text-gray-700">
                            <strong>Harga Unit:</strong> <span id="unit-price">Pilih unit terlebih dahulu</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Financing Details -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Financing Details</h3>
                <div class="space-y-4">
                    <div>
                        <label for="down_payment_percentage" class="block text-sm font-semibold text-gray-700 mb-2">Down Payment (DP) % <span class="text-red-500">*</span></label>
                        <input type="number" name="down_payment_percentage" id="down_payment_percentage" value="{{ old('down_payment_percentage', '20') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800"
                               placeholder="20" step="0.01" min="0" max="100" required>
                        @error('down_payment_percentage') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="down_payment_nominal" class="block text-sm font-semibold text-gray-700 mb-2">Down Payment (DP) Rp <span class="text-red-500">*</span></label>
                        <input type="text" name="down_payment_nominal" id="down_payment_nominal" value="{{ old('down_payment_nominal') }}"
                               class="currency-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800"
                               placeholder="0" required>
                        @error('down_payment_nominal') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="loan_term" class="block text-sm font-semibold text-gray-700 mb-2">Loan Term (Years) <span class="text-red-500">*</span></label>
                        <div class="space-y-2">
                            <input type="range" name="loan_term" id="loan_term" min="1" max="30" value="{{ old('loan_term', '15') }}"
                                   class="slider w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>1</span>
                                <span id="loan_term_value" class="font-semibold text-gray-800">15</span>
                                <span>30</span>
                            </div>
                        </div>
                        @error('loan_term') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="interest_type" class="block text-sm font-semibold text-gray-700 mb-2">Interest Type <span class="text-red-500">*</span></label>
                        <select name="interest_type" id="interest_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800" required>
                            <option value="">Select Type</option>
                            <option value="flat" {{ old('interest_type') == 'flat' ? 'selected' : '' }}>Flat Rate</option>
                            <option value="tiered" {{ old('interest_type') == 'tiered' ? 'selected' : '' }}>Tiered/Floating Rate</option>
                        </select>
                        @error('interest_type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Calculated DP:</strong> <span id="calculated-dp">Please select a unit and enter DP</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Interest Rate Configuration -->
            <div id="flat-rate-section" class="bg-gray-50 p-4 rounded-lg" style="display: none;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Flat Rate Configuration</h3>
                <div class="space-y-4">
                    <div>
                        <label for="flat_rate" class="block text-sm font-semibold text-gray-700 mb-2">Annual Interest Rate (%) <span class="text-red-500">*</span></label>
                        <input type="number" name="flat_rate" id="flat_rate" value="{{ old('flat_rate') }}" step="0.01" min="0" max="100"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800"
                               placeholder="8.5">
                        @error('flat_rate') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div id="tiered-rate-section" class="bg-gray-50 p-4 rounded-lg" style="display: none;">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Tiered Rate Configuration</h3>
                <div id="tiered-rates-container">
                    <div class="tiered-rate-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Interest Rate (%)</label>
                            <input type="number" name="tiered_rates[0][rate]" step="0.01" min="0" max="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800"
                                   placeholder="8.5">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Years)</label>
                            <input type="number" name="tiered_rates[0][years]" min="1" max="30"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800"
                                   placeholder="5">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="add-tier-btn bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-200">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-600">Add multiple tiers for floating interest rates that change over time.</p>
            </div>

    <div class="flex justify-end">
        <button type="submit" id="calculate-btn" class="bg-gray-800 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200">
            <i class="fas fa-calculator mr-2"></i>
            Calculate KPR
        </button>
    </div>
                </form>

                <!-- Loading Spinner -->
                <div id="loading-spinner" class="hidden mt-8 flex justify-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2FA769]"></div>
                        <p class="text-center mt-4 text-gray-600">Calculating...</p>
                    </div>
                </div>

                <!-- Error Container -->
                <div id="error-container" class="hidden"></div>
            </div>

            <!-- Results Section -->
            <div class="flex-1">
                <!-- Results Container -->
                <div id="results-container" class="hidden"></div>

                @if($errors->any())
        <div class="mt-8 bg-red-50 border border-red-200 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-red-800 mb-4">Error</h3>
            <ul class="list-disc list-inside text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
            </div>
        </div>

        @if(isset($calculation) && $calculation)
        <!-- Calculation Results -->
        <div class="mt-8 bg-green-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-green-800 mb-4">KPR Calculation Results</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Unit Price</h4>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($unit->price, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Down Payment</h4>
                    <p class="text-2xl font-bold text-gray-800">
                        Rp {{ number_format($downPayment, 0, ',', '.') }}
                        <br><small class="text-gray-500">
                            ({{ number_format(($downPayment / $unit->price) * 100, 2) }}% of unit price)
                        </small>
                    </p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Loan Amount</h4>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($loanAmount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Loan Term</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ $loanTerm }} Years</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Monthly Payment</h4>
                    <p class="text-2xl font-bold text-[#2FA769]">Rp {{ number_format($simulation['monthly_payment'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Total Payment</h4>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($simulation['total_payment'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h4 class="text-sm font-semibold text-gray-600">Total Interest</h4>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($simulation['total_interest'], 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Payment Schedule -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Payment Schedule</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                @if($simulation['type'] === 'tiered')
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate (%)</th>
                                @endif
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Principal</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interest</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Payment</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(array_slice($simulation['schedule'], 0, 12) as $payment) <!-- Show first year -->
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment['month'] }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment['year'] }}</td>
                                    @if($simulation['type'] === 'tiered')
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $payment['rate'] }}%</td>
                                    @endif
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($payment['principal_payment'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($payment['interest_payment'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($payment['total_payment'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($payment['remaining_balance'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(count($simulation['schedule']) > 12)
                        <p class="text-sm text-gray-600 mt-2">* Showing first 12 months. Total {{ count($simulation['schedule']) }} months.</p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Unit selection and price display
    const unitSelect = document.getElementById('unit_id');
    const unitPriceDisplay = document.getElementById('unit-price');
    const clusterSelect = document.getElementById('cluster_id');
    const downPaymentPercentageInput = document.getElementById('down_payment_percentage');
    const downPaymentNominalInput = document.getElementById('down_payment_nominal');
    const loanTermSlider = document.getElementById('loan_term');
    const loanTermValueDisplay = document.getElementById('loan_term_value');
    const calculatedDpDisplay = document.getElementById('calculated-dp');

    // Form submission handler - AJAX
    const form = document.getElementById('kpr-form');
    const submitBtn = document.getElementById('calculate-btn');
    const resultsContainer = document.getElementById('results-container');
    const loadingSpinner = document.getElementById('loading-spinner');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const interestType = document.getElementById('interest_type').value;

        // Ensure required attributes are set correctly before submission
        if (interestType === 'flat') {
            document.getElementById('flat_rate').required = true;
            // Remove required from tiered inputs
            const tieredInputs = document.querySelectorAll('#tiered-rates-container input');
            tieredInputs.forEach(input => input.required = false);
        } else if (interestType === 'tiered') {
            document.getElementById('flat_rate').required = false;
            // Add required to tiered inputs
            const tieredInputs = document.querySelectorAll('#tiered-rates-container input');
            tieredInputs.forEach(input => input.required = true);
        }

        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Calculating...';
        }
        if (loadingSpinner) loadingSpinner.classList.remove('hidden');
        if (resultsContainer) resultsContainer.classList.add('hidden');

        // Clear previous errors
        const errorContainer = document.getElementById('error-container');
        if (errorContainer) errorContainer.classList.add('hidden');

        // Prepare form data
        const formData = new FormData(form);

        // If interest type is flat, remove tiered_rates from form data
        if (interestType === 'flat') {
            // Remove all tiered_rates entries
            const keysToDelete = [];
            for (let key of formData.keys()) {
                if (key.startsWith('tiered_rates')) {
                    keysToDelete.push(key);
                }
            }
            keysToDelete.forEach(key => formData.delete(key));
        }

        // Debug: Log form data
        console.log('Submitting form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        // Send AJAX request
        fetch('{{ route("kpr-simulation.calculate") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                // Display results
                displayResults(data.data);
            } else {
                // Display errors
                displayErrors(data.errors);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            displayErrors(['An error occurred while calculating. Please try again.']);
        })
        .finally(() => {
            // Reset loading state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-calculator mr-2"></i>Calculate KPR';
            }
            if (loadingSpinner) loadingSpinner.classList.add('hidden');
        });
    });

    function displayResults(data) {
        let currentYear = 1;
        const totalYears = Math.ceil(data.total_months / 12);

        const resultsHtml = `
            <div class="bg-white p-6 rounded-lg border border-gray-300 shadow-sm">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Hasil Simulasi KPR</h2>
                    <p class="text-gray-600">Simulasi pinjaman rumah siap</p>
                </div>

                <!-- Ringkasan Properti dan Pinjaman -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Properti dan Pinjaman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-700">Harga Unit:</span>
                                <span class="font-bold text-gray-800">Rp ${data.unit_price_formatted}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Uang Muka:</span>
                                <span class="font-bold text-gray-800">Rp ${data.down_payment_formatted}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Jumlah Pinjaman:</span>
                                <span class="font-bold text-gray-800">Rp ${data.loan_amount_formatted}</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-700">Tenor:</span>
                                <span class="font-bold text-gray-800">${data.loan_term} Tahun</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Tipe Bunga:</span>
                                <span class="font-bold text-gray-800">${data.interest_type === 'flat' ? 'Flat' : 'Mengambang'}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran Bulanan -->
                <div class="bg-gray-100 p-4 rounded-lg mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Pembayaran Bulanan</h3>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-800 mb-2">Rp ${data.monthly_payment_formatted}</div>
                        <p class="text-gray-600">per bulan selama ${data.loan_term} tahun</p>
                        ${data.interest_type === 'tiered' ? '<p class="text-sm text-orange-600 mt-2 font-medium">* Pembayaran dapat berubah setiap tahun sesuai tier bunga mengambang</p>' : '<p class="text-sm text-green-600 mt-2 font-medium">* Pembayaran tetap setiap bulan</p>'}
                    </div>
                </div>

                <!-- Rincian Biaya -->
                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Biaya Total</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Pokok Pinjaman:</span>
                            <span class="font-bold text-gray-800">Rp ${data.loan_amount_formatted}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700">Total Bunga:</span>
                            <span class="font-bold text-gray-800">Rp ${data.total_interest_formatted}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between text-lg">
                            <span class="font-bold text-gray-800">Total Pembayaran:</span>
                            <span class="font-bold text-gray-800">Rp ${data.total_payment_formatted}</span>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Pembayaran Lengkap -->
                <div class="bg-white p-4 rounded-lg border border-gray-300">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Pembayaran Lengkap</h3>
                    <div id="pagination-controls" class="flex justify-between items-center mb-4">
                        <button id="prev-year" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200 disabled:opacity-50" disabled>Sebelumnya</button>
                        <span id="current-year-display" class="text-lg font-bold text-gray-800">Tahun 1</span>
                        <button id="next-year" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">Berikutnya</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-bold text-gray-800">Bulan</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-bold text-gray-800">Tahun</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-bold text-gray-800">Pokok</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-bold text-gray-800">Bunga</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-bold text-gray-800">Total</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-bold text-gray-800">Sisa</th>
                                </tr>
                            </thead>
                            <tbody id="schedule-body">
                                <!-- Schedule rows will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-gray-500 mt-3 text-center">
                        Menampilkan 12 bulan per tahun. Total ${data.total_months} bulan.
                    </p>
                </div>

                <!-- Tombol Aksi -->
                <div class="text-center mt-6">
                    <button onclick="window.print()" class="bg-gray-800 text-white px-6 py-2 rounded-lg mr-4 hover:bg-gray-700 transition duration-200">
                        Cetak Hasil
                    </button>
                    <button onclick="location.reload()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                        Hitung Lagi
                    </button>
                </div>
            </div>
        `;

        resultsContainer.innerHTML = resultsHtml;
        resultsContainer.classList.remove('hidden');

        // Function to render schedule for current year
        function renderSchedule(year) {
            const startMonth = (year - 1) * 12;
            const endMonth = Math.min(year * 12, data.schedule.length);
            const yearSchedule = data.schedule.slice(startMonth, endMonth);

            const tbody = document.getElementById('schedule-body');
            tbody.innerHTML = yearSchedule.map(item => `
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-2 text-sm text-gray-800">${item.month}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">${item.year}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">Rp ${item.principal_payment_formatted}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">Rp ${item.interest_payment_formatted}</td>
                    <td class="px-4 py-2 text-sm font-semibold text-gray-800">Rp ${item.total_payment_formatted}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">Rp ${item.remaining_balance_formatted}</td>
                </tr>
            `).join('');
        }

        // Initial render
        renderSchedule(currentYear);

        // Pagination controls
        const prevBtn = document.getElementById('prev-year');
        const nextBtn = document.getElementById('next-year');
        const yearDisplay = document.getElementById('current-year-display');

        function updatePagination() {
            prevBtn.disabled = currentYear === 1;
            nextBtn.disabled = currentYear === totalYears;
            yearDisplay.textContent = `Tahun ${currentYear}`;
        }

        prevBtn.addEventListener('click', () => {
            if (currentYear > 1) {
                currentYear--;
                renderSchedule(currentYear);
                updatePagination();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentYear < totalYears) {
                currentYear++;
                renderSchedule(currentYear);
                updatePagination();
            }
        });

        updatePagination();

        // Scroll to results
        resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function displayErrors(errors) {
        const errorHtml = `
            <div class="mt-8 bg-red-50 border border-red-200 p-6 rounded-lg">
                <h3 class="text-xl font-semibold text-red-800 mb-4">Error</h3>
                <ul class="list-disc list-inside text-red-700">
                    ${errors.map(error => `<li>${error}</li>`).join('')}
                </ul>
            </div>
        `;

        const errorContainer = document.getElementById('error-container');
        errorContainer.innerHTML = errorHtml;
        errorContainer.classList.remove('hidden');
    }

    function updateUnitPrice() {
        const selectedOption = unitSelect.options[unitSelect.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.getAttribute('data-price');
            unitPriceDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
        } else {
            unitPriceDisplay.textContent = 'Please select a unit';
        }
        updateCalculatedDp();
    }

    function updateCalculatedDp() {
        const selectedOption = unitSelect.options[unitSelect.selectedIndex];
        const percentageValue = parseFloat(downPaymentPercentageInput.value) || 0;
        const nominalValue = parseFloat(downPaymentNominalInput.value.replace(/[^\d]/g, '')) || 0;

        if (selectedOption.value) {
            const unitPrice = parseFloat(selectedOption.getAttribute('data-price'));

            if (percentageValue > 0) {
                // Update nominal based on percentage
                const calculatedNominal = (unitPrice * percentageValue) / 100;
                downPaymentNominalInput.value = new Intl.NumberFormat('id-ID').format(calculatedNominal);
                calculatedDpDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(calculatedNominal)} (${percentageValue}% of Rp ${new Intl.NumberFormat('id-ID').format(unitPrice)})`;
            } else if (nominalValue > 0) {
                // Update percentage based on nominal
                const calculatedPercentage = (nominalValue / unitPrice) * 100;
                downPaymentPercentageInput.value = calculatedPercentage.toFixed(2);
                calculatedDpDisplay.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(nominalValue)} (${calculatedPercentage.toFixed(2)}% of Rp ${new Intl.NumberFormat('id-ID').format(unitPrice)})`;
            } else {
                calculatedDpDisplay.textContent = 'Please enter DP percentage or nominal amount';
            }
        } else {
            calculatedDpDisplay.textContent = 'Please select a unit first';
        }
    }



    unitSelect.addEventListener('change', updateUnitPrice);
    downPaymentPercentageInput.addEventListener('input', updateCalculatedDp);
    downPaymentNominalInput.addEventListener('input', updateCalculatedDp);

    // Loan term slider
    loanTermSlider.addEventListener('input', function() {
        loanTermValueDisplay.textContent = this.value;
    });

    updateUnitPrice();
    updateCalculatedDp();

    // Initialize loan term display
    loanTermValueDisplay.textContent = loanTermSlider.value;

    // Filter units by cluster
    clusterSelect.addEventListener('change', function() {
        const clusterId = this.value;
        const options = unitSelect.querySelectorAll('option');

        options.forEach(option => {
            if (!option.value) return; // Skip "Select Unit" option

            const unitClusterId = option.getAttribute('data-cluster');
            if (clusterId === '' || unitClusterId === clusterId) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });

        // Reset unit selection if current selection is not in filtered cluster
        const selectedOption = unitSelect.options[unitSelect.selectedIndex];
        if (selectedOption.value && selectedOption.getAttribute('data-cluster') !== clusterId && clusterId !== '') {
            unitSelect.value = '';
            updateUnitPrice();
        }
    });

    // Interest type selection
    const interestTypeSelect = document.getElementById('interest_type');
    const flatRateSection = document.getElementById('flat-rate-section');
    const tieredRateSection = document.getElementById('tiered-rate-section');

    interestTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;

        if (selectedType === 'flat') {
            flatRateSection.style.display = 'block';
            tieredRateSection.style.display = 'none';
            document.getElementById('flat_rate').required = true;

            // Remove required from tiered rate inputs
            const tieredInputs = tieredRatesContainer.querySelectorAll('input[required]');
            tieredInputs.forEach(input => input.required = false);

        } else if (selectedType === 'tiered') {
            flatRateSection.style.display = 'none';
            tieredRateSection.style.display = 'block';
            document.getElementById('flat_rate').required = false;

            // Add required to tiered rate inputs
            const tieredInputs = tieredRatesContainer.querySelectorAll('input');
            tieredInputs.forEach(input => input.required = true);

        } else {
            flatRateSection.style.display = 'none';
            tieredRateSection.style.display = 'none';
            document.getElementById('flat_rate').required = false;

            // Remove required from tiered rate inputs
            const tieredInputs = tieredRatesContainer.querySelectorAll('input[required]');
            tieredInputs.forEach(input => input.required = false);
        }
    });

    // Tiered rate management
    const tieredRatesContainer = document.getElementById('tiered-rates-container');

    function addTieredRateItem(rate = '', years = '') {
        const itemCount = tieredRatesContainer.querySelectorAll('.tiered-rate-item').length;
        const newItem = document.createElement('div');
        newItem.className = 'tiered-rate-item grid grid-cols-1 md:grid-cols-3 gap-4 mb-4';
        newItem.innerHTML = `
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Interest Rate (%)</label>
                <input type="number" name="tiered_rates[${itemCount}][rate]" step="0.01" min="0" max="100"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800"
                       placeholder="8.5" value="${rate}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Years)</label>
                <input type="number" name="tiered_rates[${itemCount}][years]" min="1" max="30"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition duration-200 bg-white text-gray-800"
                       placeholder="5" value="${years}">
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-tier-btn bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        `;

        tieredRatesContainer.appendChild(newItem);
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const items = tieredRatesContainer.querySelectorAll('.tiered-rate-item');
        items.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-tier-btn');
            if (removeBtn) {
                if (index === 0) {
                    removeBtn.style.display = 'none';
                } else {
                    removeBtn.style.display = 'block';
                }
            }
        });
    }

    tieredRatesContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-tier-btn') || e.target.closest('.add-tier-btn')) {
            addTieredRateItem();
        } else if (e.target.classList.contains('remove-tier-btn') || e.target.closest('.remove-tier-btn')) {
            const item = e.target.closest('.tiered-rate-item');
            if (item) {
                item.remove();
                updateRemoveButtons();
                // Renumber the inputs
                const remainingItems = tieredRatesContainer.querySelectorAll('.tiered-rate-item');
                remainingItems.forEach((remainingItem, index) => {
                    const rateInput = remainingItem.querySelector('input[name*="[rate]"]');
                    const yearsInput = remainingItem.querySelector('input[name*="[years]"]');
                    if (rateInput) rateInput.name = `tiered_rates[${index}][rate]`;
                    if (yearsInput) yearsInput.name = `tiered_rates[${index}][years]`;
                });
            }
        }
    });

    updateRemoveButtons();
});
</script>
@endsection
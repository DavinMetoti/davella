<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Cluster;
use Illuminate\Http\Request;

class KPRSimulationController extends Controller
{
    /**
     * Display the KPR simulation form.
     */
    public function index()
    {
        $clusters = Cluster::with('units')->get();
        $units = Unit::available()->with('cluster')->get();

        return view('pages.kpr-simulation.index', compact('clusters', 'units'));
    }

    public function calculate(Request $request)
    {
        try {
            // Debug: Log the request data
            // \Log::info('KPR Calculation Request:', $request->all());

            // Conditional validation based on interest type
            if ($request->interest_type === 'flat') {
                $request->validate([
                    'unit_id' => 'required|exists:units,id',
                    'down_payment_percentage' => 'required|numeric|min:0|max:100',
                    'down_payment_nominal' => 'required',
                    'loan_term' => 'required|integer|min:1|max:30',
                    'interest_type' => 'required|in:flat,tiered',
                    'flat_rate' => 'required|numeric|min:0|max:100',
                ]);
            } else {
                $request->validate([
                    'unit_id' => 'required|exists:units,id',
                    'down_payment_percentage' => 'required|numeric|min:0|max:100',
                    'down_payment_nominal' => 'required',
                    'loan_term' => 'required|integer|min:1|max:30',
                    'interest_type' => 'required|in:flat,tiered',
                    'tiered_rates' => 'required|array|min:1',
                    'tiered_rates.*.rate' => 'required|numeric|min:0|max:100',
                    'tiered_rates.*.years' => 'required|integer|min:1',
                ]);
            }

            // Additional validation for tiered rates
            if ($request->interest_type === 'tiered') {
                if (empty($request->tiered_rates) || !is_array($request->tiered_rates)) {
                    $error = 'Please add at least one tiered rate configuration.';
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'errors' => [$error]]);
                    }
                    return back()->withErrors(['interest_type' => $error])->withInput();
                }

                $totalYears = 0;
                foreach ($request->tiered_rates as $tier) {
                    $totalYears += $tier['years'];
                }

                if ($totalYears != $request->loan_term) {
                    $error = 'Total years in tiered rates must equal the loan term (' . $request->loan_term . ' years). Currently: ' . $totalYears . ' years.';
                    if ($request->ajax()) {
                        return response()->json(['success' => false, 'errors' => [$error]]);
                    }
                    return back()->withErrors(['tiered_rates' => $error])->withInput();
                }
            }

            $unit = Unit::find($request->unit_id);
            if (!$unit) {
                return response()->json(['success' => false, 'errors' => ['Selected unit not found.']]);
            }
            $unit->price = (float) $unit->price;
            $dpPercentage = $request->down_payment_percentage;
            // Parse nominal value from Indonesian currency format (e.g., "24.952.078,4" -> 24952078.4)
            $dpNominal = (float) str_replace(',', '.', str_replace('.', '', $request->down_payment_nominal));

            if (!is_numeric($dpNominal) || $dpNominal < 0) {
                $error = 'Down payment nominal must be a valid number greater than or equal to 0.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => [$error]]);
                }
                return back()->withErrors(['down_payment_nominal' => $error])->withInput();
            }

            // Calculate actual down payment amount using percentage
            $downPayment = ($unit->price * $dpPercentage) / 100;

            // Validate that calculated DP matches the nominal input (with small tolerance for rounding)
            $tolerance = 10000; // 10k tolerance for rounding differences
            if (abs($downPayment - $dpNominal) > $tolerance) {
                $error = 'Down payment percentage and nominal values do not match. Please check your inputs.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => [$error]]);
                }
                return back()->withErrors(['down_payment_percentage' => $error])->withInput();
            }

            // Validate that down payment doesn't exceed unit price
            if ($downPayment >= $unit->price) {
                $error = 'Down payment cannot be equal to or greater than unit price.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'errors' => [$error]]);
                }
                return back()->withErrors(['down_payment_percentage' => $error])->withInput();
            }

            $loanTerm = $request->loan_term;
            $loanAmount = $unit->price - $downPayment;

            $simulation = [];

            if ($request->interest_type === 'flat') {
                $simulation = $this->calculateFlatRate($loanAmount, $request->flat_rate, $loanTerm);
            } else {
                $simulation = $this->calculateTieredRate($loanAmount, $request->tiered_rates, $loanTerm);
            }

            $clusters = Cluster::with('units')->get();
            $units = Unit::available()->with('cluster')->get();

            // Debug: Log successful calculation
            // \Log::info('KPR Calculation successful, returning view with simulation data', [
            //     'unit_id' => $unit->id,
            //     'loan_amount' => $loanAmount,
            //     'simulation_type' => $simulation['type'] ?? 'unknown'
            // ]);

            // Format numbers for display
            $formattedData = [
                'unit_price_formatted' => number_format($unit->price, 0, ',', '.'),
                'down_payment_formatted' => number_format($downPayment, 0, ',', '.'),
                'loan_amount_formatted' => number_format($loanAmount, 0, ',', '.'),
                'loan_term' => $loanTerm,
                'interest_type' => $request->interest_type,
                'monthly_payment_formatted' => number_format($simulation['monthly_payment'], 0, ',', '.'),
                'total_payment_formatted' => number_format($simulation['total_payment'], 0, ',', '.'),
                'total_interest_formatted' => number_format($simulation['total_interest'], 0, ',', '.'),
                'schedule' => array_map(function($item) {
                    return [
                        'month' => $item['month'],
                        'year' => $item['year'],
                        'principal_payment_formatted' => number_format($item['principal_payment'], 0, ',', '.'),
                        'interest_payment_formatted' => number_format($item['interest_payment'], 0, ',', '.'),
                        'total_payment_formatted' => number_format($item['total_payment'], 0, ',', '.'),
                        'remaining_balance_formatted' => number_format($item['remaining_balance'], 0, ',', '.'),
                    ];
                }, $simulation['schedule']),
                'total_months' => count($simulation['schedule']),
            ];

            // Add DP description
            $formattedData['dp_description'] = $dpPercentage . '% of unit price';

            // Return JSON for AJAX requests
            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $formattedData]);
            }

            $clusters = Cluster::with('units')->get();
            $units = Unit::available()->with('cluster')->get();

            return view('pages.kpr-simulation.index', compact('clusters', 'units', 'simulation', 'unit', 'downPayment', 'loanAmount', 'loanTerm'))
                ->with('calculation', true);

        } catch (\Exception $e) {
            // Log the error and return with error message
            // \Log::error('KPR Simulation Error: ' . $e->getMessage());
            $error = 'An error occurred during calculation. Please check your inputs and try again.';

            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => [$error]]);
            }

            return back()->withErrors(['general' => $error])->withInput();
        }
    }

    /**
     * Calculate flat rate mortgage.
     */
    private function calculateFlatRate($loanAmount, $annualRate, $years)
    {
        $totalMonths = $years * 12;

        // Flat rate calculation: interest is calculated on original loan amount for entire period
        $monthlyInterest = $loanAmount * ($annualRate / 100) / 12;
        $monthlyPrincipal = $loanAmount / $totalMonths;
        $monthlyPayment = $monthlyInterest + $monthlyPrincipal;

        $schedule = [];
        $remainingBalance = $loanAmount;

        for ($month = 1; $month <= $totalMonths; $month++) {
            $year = ceil($month / 12);
            $principal_payment = $monthlyPrincipal;

            // Adjust last payment to avoid negative balance
            if ($month == $totalMonths) {
                $principal_payment = $remainingBalance;
            }

            $total_payment = $monthlyInterest + $principal_payment;

            $schedule[] = [
                'month' => $month,
                'year' => $year,
                'principal_payment' => $principal_payment,
                'interest_payment' => $monthlyInterest,
                'total_payment' => $total_payment,
                'remaining_balance' => $remainingBalance - $principal_payment
            ];

            $remainingBalance -= $principal_payment;
        }

        return [
            'type' => 'flat',
            'monthly_payment' => $monthlyPayment,
            'total_payment' => array_sum(array_column($schedule, 'total_payment')),
            'total_interest' => $monthlyInterest * $totalMonths,
            'schedule' => $schedule,
        ];
    }

    /**
     * Calculate tiered rate mortgage.
     */
    /**
     * Calculate tiered rate mortgage.
     */
    private function calculateTieredRate($loanAmount, $tieredRates, $years)
    {
        $totalMonths = $years * 12;
        $schedule = [];
        $remainingBalance = $loanAmount;
        $totalPayment = 0;
        $totalInterest = 0;

        // Sort tiers by cumulative years to ensure proper ordering
        $sortedTiers = [];
        $cumulativeYears = 0;

        foreach ($tieredRates as $tier) {
            $cumulativeYears += $tier['years'];
            $sortedTiers[] = [
                'rate' => $tier['rate'] / 100 / 12, // Convert to monthly rate
                'annual_rate' => $tier['rate'],
                'years' => $tier['years'],
                'start_month' => ($cumulativeYears - $tier['years']) * 12 + 1,
                'end_month' => $cumulativeYears * 12
            ];
        }

        for ($month = 1; $month <= $totalMonths; $month++) {
            // Find current tier
            $currentRate = 0;
            $currentAnnualRate = 0;
            foreach ($sortedTiers as $tier) {
                if ($month >= $tier['start_month'] && $month <= $tier['end_month']) {
                    $currentRate = $tier['rate'];
                    $currentAnnualRate = $tier['annual_rate'];
                    break;
                }
            }

            // Safety check for rate
            if ($currentRate <= 0) {
                $currentRate = 0.005 / 12; // Fallback monthly rate (0.5% annual)
                $currentAnnualRate = 0.5;
            }

            // Calculate interest for this month
            $interestPayment = $remainingBalance * $currentRate;

            // For the last payment, ensure we don't pay more than remaining balance
            if ($month == $totalMonths) {
                $principalPayment = $remainingBalance;
                $totalPaymentAmount = $interestPayment + $principalPayment;
            } else {
                // Calculate principal payment - this is an estimate
                // In a real tiered rate mortgage, the payment might be recalculated when rates change
                // For simplicity, we'll use a standard amortization approach
                if ($remainingBalance > 0) {
                    // Recalculate payment based on remaining term and current rate
                    $remainingTermMonths = $totalMonths - $month + 1;
                    $monthlyPayment = $remainingBalance * ($currentRate * pow(1 + $currentRate, $remainingTermMonths)) /
                                   (pow(1 + $currentRate, $remainingTermMonths) - 1);

                    $principalPayment = $monthlyPayment - $interestPayment;

                    // Ensure we don't overpay principal
                    if ($principalPayment > $remainingBalance) {
                        $principalPayment = $remainingBalance;
                        $monthlyPayment = $interestPayment + $principalPayment;
                    }
                } else {
                    $monthlyPayment = 0;
                    $principalPayment = 0;
                    $interestPayment = 0;
                }

                $totalPaymentAmount = $monthlyPayment;
            }

            $remainingBalance -= $principalPayment;
            $totalPayment += $totalPaymentAmount;
            $totalInterest += $interestPayment;

            $schedule[] = [
                'month' => $month,
                'year' => ceil($month / 12),
                'rate' => $currentAnnualRate,
                'principal_payment' => $principalPayment,
                'interest_payment' => $interestPayment,
                'total_payment' => $totalPaymentAmount,
                'remaining_balance' => max(0, $remainingBalance),
            ];
        }

        return [
            'type' => 'tiered',
            'monthly_payment' => $schedule[0]['total_payment'] ?? 0,
            'total_payment' => $totalPayment,
            'total_interest' => $totalInterest,
            'schedule' => $schedule,
        ];
    }
}
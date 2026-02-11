<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->decimal('loan_amount', 15, 2)->nullable()->after('dp_plan');
            $table->string('interest_type', 20)->nullable()->after('loan_amount'); // 'flat' or 'tiered'
            $table->decimal('flat_rate', 5, 2)->nullable()->after('interest_type');
            $table->json('tiered_rates')->nullable()->after('flat_rate'); // JSON array of rates
            $table->integer('loan_term')->nullable()->after('tiered_rates'); // in years
            $table->decimal('monthly_payment', 15, 2)->nullable()->after('loan_term');
            $table->decimal('total_payment', 15, 2)->nullable()->after('monthly_payment');
            $table->decimal('total_interest', 15, 2)->nullable()->after('total_payment');
            $table->decimal('remaining_amount', 15, 2)->nullable()->after('total_interest'); // amount still needed to pay
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'loan_amount',
                'interest_type',
                'flat_rate',
                'tiered_rates',
                'loan_term',
                'monthly_payment',
                'total_payment',
                'total_interest',
                'remaining_amount'
            ]);
        });
    }
};

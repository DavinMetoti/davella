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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_code', 50)->unique();
            $table->dateTime('reservation_date');
            $table->dateTime('expired_at');

            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->decimal('price_snapshot', 15, 2);
            $table->json('promo_snapshot')->nullable();

            $table->string('customer_name', 100);
            $table->string('customer_phone', 20);
            $table->string('ktp_number', 30);

            $table->foreignId('sales_id')->constrained('users')->onDelete('cascade');

            $table->string('payment_method', 20)->nullable();
            $table->decimal('booking_fee', 15, 2)->nullable();
            $table->decimal('dp_plan', 15, 2)->nullable();

            $table->string('status', 20);

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes
            $table->index(['unit_id', 'status'], 'idx_unit_status');
            $table->index('sales_id', 'idx_sales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};

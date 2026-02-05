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
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('site_plan_path')->nullable(); // Path to uploaded site plan file
            $table->string('area_size')->nullable(); // Size in m² or hectares
            $table->integer('total_units')->default(0);
            $table->integer('available_units')->default(0);
            $table->decimal('price_range_min', 15, 2)->nullable();
            $table->decimal('price_range_max', 15, 2)->nullable();
            $table->unsignedBigInteger('developer_id')->nullable();
            $table->foreign('developer_id')->references('id')->on('companies')->onDelete('set null');
            $table->year('year_built')->nullable();
            $table->json('facilities')->nullable(); // JSON array of facilities
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};

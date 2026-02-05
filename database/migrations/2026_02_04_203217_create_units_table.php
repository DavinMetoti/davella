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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cluster_id')->constrained('clusters')->onDelete('cascade');
            $table->string('name'); // Nama unit
            $table->string('block'); // Blok
            $table->string('number'); // Kav/Number
            $table->string('house_type'); // Tipe Rumah
            $table->decimal('land_area', 10, 2); // LT - Luas Tanah
            $table->decimal('building_area', 10, 2); // LB - Luas Bangunan
            $table->integer('progress')->default(0); // Progres (%)
            $table->enum('status', ['available', 'reserved', 'booked'])->default('available'); // Status
            $table->text('coordinates')->nullable(); // JSON string untuk menyimpan titik koordinat kompleks jika diperlukan
            $table->timestamps();

            // Indexes
            $table->index(['cluster_id', 'status']);
            $table->index(['block', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};

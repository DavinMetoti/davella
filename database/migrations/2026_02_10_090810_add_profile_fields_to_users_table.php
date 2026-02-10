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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('email');
            $table->string('phone')->nullable()->after('profile_photo');
            $table->text('address')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('address');
            $table->date('date_of_birth')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'phone', 'address', 'bio', 'date_of_birth']);
        });
    }
};

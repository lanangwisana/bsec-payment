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
        Schema::table('students', function (Blueprint $table) {
            $table->date('registration_date')->nullable();
            $table->string('parent_name')->nullable();
            $table->decimal('monthly_fee', 15, 2)->default(0);
            $table->text('address')->nullable();
            $table->string('birth_place_date')->nullable(); // Persyaratan formulir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['registration_date', 'parent_name', 'monthly_fee', 'address', 'birth_place_date']);
        });
    }
};

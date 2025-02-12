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
        Schema::create('pv_subdatas', function (Blueprint $table) {
            $table->id();
            $table->decimal('profit', 10, 2);
            $table->decimal('co2_eq', 6, 2);
            $table->decimal('trees_eq', 6, 2);
            $table->decimal('coal_eq', 6, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pv_subdatas');
    }
};

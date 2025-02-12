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
        Schema::create('subdata', function (Blueprint $table) {
            $table->id();
            $table->integer('hargaKwh');
            $table->decimal('co2eq', 6, 3);
            $table->integer('hargaPdam');
            $table->decimal('kwhAirPerMeterKubik', 6, 3);
            $table->decimal('trees_eq', 6, 2);
            $table->decimal('coal_eq', 6, 2);
            $table->char('decimal_sep', 4);
            $table->char('thousand_sep', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdata');
    }
};

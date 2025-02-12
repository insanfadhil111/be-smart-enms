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
        Schema::create('goodwe_data', function (Blueprint $table) {
            $table->id();
            $table->float('P');
            $table->float('today_generation');
            $table->float('total_generation');
            $table->float('today_income');
            $table->float('total_income');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goodwe_data');
    }
};

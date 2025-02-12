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
        Schema::create('mdp_kwh', function (Blueprint $table) {
            $table->id();
            $table->integer('kwh_1');
            $table->integer('kwh_2');
            $table->integer('kwh_3');
            $table->integer('kwh_4');
            $table->integer('kwh_5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mdp_kwh');
    }
};

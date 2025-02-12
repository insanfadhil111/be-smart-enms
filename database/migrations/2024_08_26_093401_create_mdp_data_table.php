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
        Schema::create('mdp_data', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kwh');
            $table->decimal('Van', 6, 2);
            $table->decimal('Vbn', 6, 2);
            $table->decimal('Vcn', 6, 2);
            $table->decimal('Ia', 6, 2);
            $table->decimal('Ib', 6, 2);
            $table->decimal('Ic', 6, 2);
            $table->decimal('It', 8, 2);
            $table->decimal('Pa', 6, 2);
            $table->decimal('Pb', 6, 2);
            $table->decimal('Pc', 6, 2);
            $table->decimal('Pt', 8, 2);
            $table->decimal('Qa', 6, 2);
            $table->decimal('Qb', 6, 2);
            $table->decimal('Qc', 6, 2);
            $table->decimal('Qt', 8, 2);
            $table->decimal('pf', 6, 2);
            $table->decimal('f', 6, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mdp_data');
    }
};

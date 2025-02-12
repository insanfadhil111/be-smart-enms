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
        Schema::create('mpp_data', function (Blueprint $table) {
            $table->id();
            $table->float('P');
            $table->float('gridVoltageR')->default(0);
            $table->float('gridpowerR')->default(0);
            $table->float('gridFreqR')->default(0);
            $table->float('gridCurrR')->default(0);
            $table->float('ACOutVolR')->default(0);
            $table->float('ACOutPowR')->default(0);
            $table->float('ACOutFreqR')->default(0);
            $table->float('ACOutCurrR')->default(0);
            $table->float('OUTLoadPerc')->default(0);
            $table->float('PVInPow1')->default(0);
            $table->float('PVInPow2')->default(0);
            $table->float('PVInVol1')->default(0);
            $table->float('PVInVol2')->default(0);
            $table->float('temp')->default(0);
            $table->integer('devstatus')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpp_data');
    }
};

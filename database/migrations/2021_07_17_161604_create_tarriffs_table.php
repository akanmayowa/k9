<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarriffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarriffs', function (Blueprint $table) {
            $table->id();
            $table->float('weight_start');
            $table->float('weight_end');
            $table->integer('zone_1_cost_in_cents');
            $table->integer('zone_2_cost_in_cents');
            $table->integer('zone_3_cost_in_cents');
            $table->integer('zone_4_cost_in_cents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarriffs');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarriffZonningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarriff_zonnings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('departure_location_id');
            $table->bigInteger('destination_location_id');
            $table->bigInteger('zone_id');
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
        Schema::dropIfExists('tarriff_zonning');
    }
}

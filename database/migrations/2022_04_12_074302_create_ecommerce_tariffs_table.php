<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceTariffsTable extends Migration
{

    public function up()
    {
        Schema::create('ecommerce_tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('weight_start',13,4);
            $table->decimal('weight_end',13,4);
            $table->decimal('zone_1_cost',13,4);
            $table->decimal('zone_2_cost',13,4);
            $table->decimal('zone_3_cost',13,4);
            $table->decimal('zone_4_cost',13,4);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('ecommerce_tariffs');
    }
}

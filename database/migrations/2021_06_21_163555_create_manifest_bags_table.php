<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifest_bags', function (Blueprint $table) {
            $table->id();
            $table->string('manifest_id');
            $table->string('shipment_type'); //Foward or Reverse
            $table->string('seal_number')->unique();
            $table->bigInteger('number_of_waybills');
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
        Schema::dropIfExists('manifest_bags');
    }
}

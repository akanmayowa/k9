<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShipmentTypeToManifestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manifests', function (Blueprint $table) {
            $table->string('shipment_type')->nullable(); //Foward or Reverse
            $table->string('seal_number')->nullable()->unique(); // they can decide not to provide seal number
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manifests', function (Blueprint $table) {
            $table->dropColumn('shipment_type'); //Foward or Reverse
            $table->dropColumn('seal_number');
        });
    }
}

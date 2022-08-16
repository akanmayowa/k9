<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateManifestsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manifests', function (Blueprint $table) {
            $table->bigInteger('transport_type_id')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phonenumber')->nullable();
            $table->string('truck_platenumber')->nullable();
            $table->integer('number_of_bags')->nullable();
            $table->string('truck_seal_number')->nullable();
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
            $table->dropColumn('transport_type_id');
            $table->dropColumn('driver_name');
            $table->dropColumn('driver_phonenumber');
            $table->dropColumn('truck_platenumber');
            $table->dropColumn('number_of_bags');
            $table->dropColumn('truck_seal_number');
        });
    }
}

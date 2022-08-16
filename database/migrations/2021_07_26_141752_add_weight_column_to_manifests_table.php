<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeightColumnToManifestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manifests', function (Blueprint $table) {
            $table->float('departure_weight')->default(0);
            $table->float('arrival_weight')->default(0);
            $table->integer('next_site_location_id')->nullable();
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
            $table->dropColumn('departure_weight');
            $table->dropColumn('arrival_weight');
            $table->dropColumn('next_site_location_id');
        });
    }
}

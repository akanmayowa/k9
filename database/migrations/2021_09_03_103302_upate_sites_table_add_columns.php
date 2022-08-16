<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpateSitesTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            //These flags are present because Speedaf is yet to have a clear distinction between these sites
            $table->integer('is_a_direct_site')->default(0);
            $table->integer('is_a_warehouse')->default(0);
            $table->integer('is_a_distribution_center')->default(0);
            $table->integer('is_a_nipost_site')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('is_a_direct_site');
            $table->dropColumn('is_a_warehouse');
            $table->dropColumn('is_a_distribution_center');
            $table->dropColumn('is_a_nipost_site');
        });
    }
}

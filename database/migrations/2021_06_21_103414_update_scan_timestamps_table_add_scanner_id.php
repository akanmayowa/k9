<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateScanTimestampsTableAddScannerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scan_timestamps', function (Blueprint $table) {
            //remove the nullable later
            $table->bigInteger('scanner_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scan_timestamps', function (Blueprint $table) {
            $table->dropColumn('scanner_id');
        });
    }
}

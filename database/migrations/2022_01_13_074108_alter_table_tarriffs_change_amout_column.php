<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTarriffsChangeAmoutColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarriffs', function (Blueprint $table) {
            $table->float('zone_1_cost_in_cents')->change();
            $table->float('zone_2_cost_in_cents')->change();
            $table->float('zone_3_cost_in_cents')->change();
            $table->float('zone_4_cost_in_cents')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarriffs', function (Blueprint $table) {
            //
        });
    }
}

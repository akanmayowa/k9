<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddK9ColumnsToWaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->float('departure_weight')->nullable();
            $table->float('arrival_weight')->nullable();
            $table->float('weight')->nullable();
            $table->string('main_id')->nullable();
            $table->float('quantity')->nullable();
            $table->string('goods_type')->nullable();
            $table->string('send_company')->nullable();
            $table->bigInteger('scanner')->nullable();
            $table->string('scan_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->dropColumn('departure_weight');
            $table->dropColumn('arrival_weight');
            $table->dropColumn('weight');
            $table->dropColumn('main_id');
            $table->dropColumn('quantity');
            $table->dropColumn('goods_type');
            $table->dropColumn('send_company');
            $table->dropColumn('scanner');
            $table->dropColumn('scan_date');
        });
    }
}

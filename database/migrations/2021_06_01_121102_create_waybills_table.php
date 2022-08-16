<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waybills', function (Blueprint $table) {
            //Run this migration again
            $table->bigInteger('id')->primary(); //14+, no auto increments, k9 waybill number
            $table->bigInteger('manifest_id'); //v2 might use uuid
            $table->bigInteger('scan_site_id'); // k9 site Id
            $table->bigInteger('next_site_id'); // k9 site Id
			$table->integer('status')->default(0); //IN-TRANSIT, DECLINED, CANCELLED
            $table->bigInteger('created_by');
            $table->bigInteger('acknwoledged_by')->nullable();
            $table->dateTime('acknwoledged_at')->nullable();
			$table->softDeletes();
			$table->bigInteger('updated_by');
			$table->timestamps(); //created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waybills');
    }
}

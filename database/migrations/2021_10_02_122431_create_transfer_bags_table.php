<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_bags', function (Blueprint $table) {
            $table->id();
            $table->string('bag_id');
            $table->bigInteger('transfer_id'); //v2 might use uuid
			$table->integer('status')->default(0); //IN-TRANSIT, DECLINED, CANCELLED
            $table->bigInteger('departure_site_id'); // k9 site Id
            $table->bigInteger('destination_site_id'); // k9 site Id
            $table->bigInteger('created_by');
            $table->bigInteger('acknowledged_by')->nullable();
            $table->dateTime('acknowledged_at')->nullable();
			$table->softDeletes();
			$table->bigInteger('updated_by')->nullable();
			$table->timestamps(); //created_at, updated_at

            $table->unique(array('bag_id', 'transfer_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfer_bags');
    }
}

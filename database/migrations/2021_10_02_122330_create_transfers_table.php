<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->bigInteger('departure_site_id');
            $table->bigInteger('destination_site_id');
            $table->bigInteger('created_by');
            $table->dateTime('acknowledged_at')->nullable();
			$table->bigInteger('acknowledged_by')->nullable();
			$table->bigInteger('updated_by')->nullable();
			$table->softDeletes();
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
        Schema::dropIfExists('transfers');
    }
}

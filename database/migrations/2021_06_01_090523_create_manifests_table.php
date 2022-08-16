<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifests', function (Blueprint $table) {
            $table->id(); //primary Key , AutoIncrement //11 in lenghth starting from 50000000000
            $table->bigInteger('scan_site_id'); // k9 site Id
            $table->bigInteger('next_site_id'); // k9 site Id
			$table->integer('status')->default(0); //IN_TRANSIT(0),ACKNOWLEDGED(1), PARTIALLY_ACKNOWLEDGE(2), CANCELLED(3)
            $table->integer('is_flagged')->default(0);
            $table->bigInteger('created_by'); // k9 account id of the person who created the manifest
			$table->bigInteger('acknowledged_by')->nullable();//v2 this will change when we have more status
			$table->bigInteger('updated_by');
            $table->dateTime('acknowledged_at')->nullable(); // time is used to fire reminders, null represents not sent
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
        Schema::dropIfExists('manifests');
    }
}

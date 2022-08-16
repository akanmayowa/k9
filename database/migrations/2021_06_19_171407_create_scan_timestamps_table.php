<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScanTimestampsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_timestamps', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->bigInteger('scan_site_id'); // k9 site Id
            $table->bigInteger('next_site_id'); // k9 site Id
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->integer('scan_type');
            $table->integer('cancelled')->default(0);
            $table->string('tag');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scan_timestamps');
    }
}

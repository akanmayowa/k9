<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bags', function (Blueprint $table) {
            $table->string('id')->primary(); //string, non-autoincrement, unique  // DCDC-001 code + type
            $table->string('type'); // DCDC
            $table->integer('number');
            $table->integer('status')->default(0);
            $table->bigInteger('current_manifest_or_transfer_id')->nullable();
            $table->bigInteger('next_or_current_site_id')->nullable(); //when status is lost, location is NULL
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('bags');
    }
}

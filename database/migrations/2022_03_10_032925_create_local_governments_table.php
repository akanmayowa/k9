<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalGovernmentsTable extends Migration
{
   
    public function up()
    {
        Schema::create('local_governments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('state_id')->references('id')->on('states')->onUpdate('CASCADE')->onDelete('cascade');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }
   
    public function down()
    {
        Schema::dropIfExists('local_governments');
    }
}

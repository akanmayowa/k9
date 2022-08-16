<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteTypesTable extends Migration
{
    public function up()
    {
        Schema::create('site_types', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('name');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('site_types');
    }
}

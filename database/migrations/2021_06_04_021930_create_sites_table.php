<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('name');
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by'); //k9x update
            $table->bigInteger('parent_site_id'); //Finance Center do not really have parent site
            $table->integer('is_disabled')->default(0); //assumption 1 is disabled. Test case (234401, 234502)ask mr White, which is disabled site column?
                                                        //like BL_Delete Column in Employee_View Table
            $table->bigInteger('site_type_id');


            //I won't allow k9 info to override these settings made only in k9x
            $table->integer('can_dispatch_or_acknowledge_manifest')->default(1);  //Yes by default, Go change it manually
            $table->string('address')->nullable(true);
            $table->bigInteger('state_id')->nullable(true); //Lagos ? Edo ?
            $table->bigInteger('country_id')->nullable(true);
            $table->integer('is_a_test_site')->default(0);  //They should not be able to send to or receive from Test WebSite in Real Life
            $table->integer('is_a_franchise')->default(0); // //go and set it manually jor, no column to know if a site is a franchasee now ?
            $table->softDeletes();
            $table->bigInteger('updated_on_k9_by'); // k9 stores this as string
            $table->dateTime('updated_on_k9_at');
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
        Schema::dropIfExists('sites');
    }
}

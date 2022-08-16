<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateApiUsersTable extends Migration
{

    public function up()
    {
        Schema::create('api_users', function (Blueprint $table) {
            $table->string('id')->unique()->primaryKey(); ///appcode
            $table->string('name');
            $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->string('is_active')->default(1);  //status: active = 1 and disabled = 0
            $table->string('access_type')->default('test');///access_type: test = 0 and production = 1
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }


    

    public function down()
    {
        Schema::dropIfExists('api_users');
    }
}



//php artisan migrate:refresh --path /database/migrations/2022_03_09_090839_create_api_users_table.php

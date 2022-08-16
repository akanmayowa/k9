<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigInteger('id')->primary(); //integer, non-autoincrement, unique
            $table->string('password'); // //{it's' prefferred not to use k9 system}
            $table->string('name'); //required
			$table->bigInteger('site_id');
            // to be verified in V2
            //A user can have multiple accounts wtih the same email
            $table->string('email')->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('alternate_email')->nullable();
            $table->timestamp('alternate_email_verified_at')->nullable();
            $table->string('phone_number')->nullable(); //required, To be verified in V2
            $table->string('phone_number_verification_code')->unique()->nullable();
            $table->timestamp('phone_number_verified_at')->nullable();
            $table->string('alternate_phone_number')->nullable();
            $table->string('alternate_phone_number_verification_code')->unique()->nullable();
            $table->timestamp('alternate_phone_number_verified_at')->nullable();
			$table->softDeletes();
            $table->rememberToken();
			$table->bigInteger('created_by');
            $table->bigInteger('updated_by'); //k9x update
			$table->integer('is_disabled')->default(0);
            $table->integer('is_super_administrator')->default(0);
			$table->bigInteger('updated_on_k9_by'); // k9 stores this as string
            $table->dateTime('updated_on_k9_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

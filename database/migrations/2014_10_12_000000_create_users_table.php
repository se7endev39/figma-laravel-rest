<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('account_type',15)->nullable();
            $table->string('business_name')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
			$table->string('phone',30)->unique();            
            $table->string('password');
			$table->string('user_type',30);
            $table->string('profile_picture')->nullable();
            $table->integer('status');
            $table->string('account_status',20)->nullable();
            $table->string('prince_plan_type',20)->nullable();
            $table->timestamp('document_submitted_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
			$table->bigInteger('refer_user_id')->nullable();
			$table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
			$table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

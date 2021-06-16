<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_type',50);
            $table->bigInteger('currency_id');
            $table->decimal('maintenance_fee',10,2);
            $table->decimal('interest_rate',10,2);
            $table->string('interest_period',20);
            $table->string('payout_period',20);
            $table->integer('auto_create');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('account_types');
    }
}

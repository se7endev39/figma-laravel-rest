<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWireDepositRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wire_deposit_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('transaction_id',100);
			$table->bigInteger('user_id');
            $table->bigInteger('credit_account');
            $table->decimal('amount',10,2);
            $table->decimal('charge',10,2);
            $table->string('status',10);
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
        Schema::dropIfExists('wire_deposit_requests');
    }
}

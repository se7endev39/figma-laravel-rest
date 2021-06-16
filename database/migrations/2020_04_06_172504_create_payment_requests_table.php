<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('account_id');
			$table->decimal('amount',10,2);
			$table->string('status',15);
			$table->text('description')->nullable();
			$table->bigInteger('created_by');
			$table->bigInteger('paid_by')->nullable();
			$table->bigInteger('transaction_id')->nullable();
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
        Schema::dropIfExists('payment_requests');
    }
}

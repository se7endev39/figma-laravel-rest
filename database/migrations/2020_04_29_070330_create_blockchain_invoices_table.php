<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockchainInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blockchain_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_id',100);
			$table->bigInteger('user_id');
            $table->bigInteger('credit_account');
            $table->decimal('amount',10,2);
            $table->string('btc_address');
			$table->integer('status');
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
        Schema::dropIfExists('blockchain_invoices');
    }
}

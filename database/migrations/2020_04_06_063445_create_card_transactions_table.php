<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('card_id');
            $table->string('dr_cr',2);
            $table->decimal('amount',10,2);
            $table->integer('status');
            $table->text('note')->nullable();
            $table->bigInteger('transaction_id')->nullable();
			$table->bigInteger('created_by')->nullable();
			$table->bigInteger('updated_by')->nullable();
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
        Schema::dropIfExists('card_transactions');
    }
}

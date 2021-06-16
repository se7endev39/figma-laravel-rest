<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('trans_date');
            $table->bigInteger('chart_of_account_id')->unsigned();
            $table->string('type',15);
            $table->decimal('amount',10,2);
            $table->string('reference')->nullable();
            $table->text('note')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
			
			$table->foreign('chart_of_account_id')->references('id')->on('chart_of_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_transactions');
    }
}

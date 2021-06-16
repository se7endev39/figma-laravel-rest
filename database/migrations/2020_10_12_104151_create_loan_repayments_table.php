<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('loan_id');
            $table->date('repayment_date');
            $table->decimal('amount_to_pay',10,2);
            $table->decimal('penalty',10,2);
            $table->decimal('principal_amount',10,2);
            $table->decimal('interest',10,2);
            $table->decimal('balance',10,2);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('loan_repayments');
    }
}

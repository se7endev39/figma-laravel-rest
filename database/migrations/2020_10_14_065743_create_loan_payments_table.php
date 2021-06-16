<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('loan_id');
            $table->date('paid_at');
            $table->decimal('late_penalties',10,2);
            $table->decimal('interest',10,2);
            $table->decimal('amount_to_pay',10,2);
            $table->text('remarks')->nullable();
            $table->bigInteger('user_id');
            $table->bigInteger('transaction_id');
            $table->bigInteger('repayment_id');
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
        Schema::dropIfExists('loan_payments');
    }
}

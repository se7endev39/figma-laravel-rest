<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('loan_id',30)->nullable();
            $table->bigInteger('loan_product_id')->unsigned();
            $table->bigInteger('borrower_id')->unsigned();
            $table->bigInteger('account_id')->unsigned();
            $table->date('first_payment_date');
            $table->date('release_date')->nullable();
            $table->decimal('applied_amount',10,2);
            $table->decimal('total_payable',10,2)->nullable();
            $table->decimal('total_paid',10,2)->nullable();
            $table->decimal('late_payment_penalties',10,2);
            $table->text('attachment')->nullable();
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->integer('status')->default(0);
            $table->date('approved_date')->nullable();
            $table->bigInteger('approved_user_id')->nullable();
            $table->bigInteger('created_user_id')->nullable();
            $table->timestamps();
			
			//$table->foreign('loan_product_id')->references('id')->on('loan_products')->onDelete('cascade');
			//$table->foreign('borrower_id')->references('id')->on('users')->onDelete('cascade');
			//$table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}

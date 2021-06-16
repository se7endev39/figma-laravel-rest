<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanCollateralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_collaterals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('loan_id')->unsigned();
            $table->string('name');
            $table->string('collateral_type');
            $table->string('serial_number')->nullable();
            $table->decimal('estimated_price',10,2);
            $table->text('attachments')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
			
			//$table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collaterals');
    }
}

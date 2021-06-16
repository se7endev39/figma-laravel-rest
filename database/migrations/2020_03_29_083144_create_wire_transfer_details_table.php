<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWireTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wire_transfer_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transaction_id');
            $table->string('swift',50);
            $table->string('bank_name');
            $table->text('bank_address')->nullable();
            $table->string('bank_country');
            $table->string('rtn')->nullable();
            $table->string('customer_name');
            $table->text('customer_address')->nullable();
            $table->string('customer_iban',50);
            $table->string('currency',3);
            $table->decimal('amount',10,2);
            $table->text('reference_message');
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
        Schema::dropIfExists('wire_transfer_details');
    }
}

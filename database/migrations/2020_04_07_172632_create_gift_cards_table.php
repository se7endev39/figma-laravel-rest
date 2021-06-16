<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('currency_id');
            $table->bigInteger('transaction_id')->nullable();
            $table->decimal('amount',10,2);
            $table->string('code',20);
            $table->integer('status');
            $table->bigInteger('created_by');
            $table->bigInteger('redeem_by')->nullable();
            $table->datetime('redeem_date')->nullable();
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
        Schema::dropIfExists('gift_cards');
    }
}

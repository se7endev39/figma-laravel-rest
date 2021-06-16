<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBtcAmountFieldToBlockchainInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blockchain_invoices', function (Blueprint $table) {
            $table->string('btc_amount')->after('btc_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blockchain_invoices', function (Blueprint $table) {
            $table->dropColumn(['btc_amount']);
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeesToAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_types', function (Blueprint $table) {
            $table->decimal('tba_fee',10,2)->after('description');
            $table->string('tba_fee_type')->default('fixed')->after('tba_fee');
            $table->decimal('tbu_fee',10,2)->after('tba_fee_type');
			$table->string('tbu_fee_type')->default('fixed')->after('tbu_fee');
			$table->decimal('cft_fee',10,2)->after('tbu_fee_type');
			$table->string('cft_fee_type')->default('fixed')->after('cft_fee');
			$table->decimal('owt_fee',10,2)->after('cft_fee_type');
			$table->string('owt_fee_type')->default('fixed')->after('owt_fee');
			$table->decimal('iwt_fee',10,2)->after('owt_fee_type');
			$table->string('iwt_fee_type')->default('fixed')->after('iwt_fee');
			$table->decimal('payment_fee',10,2)->after('iwt_fee_type');
			$table->string('payment_fee_type')->default('fixed')->after('payment_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_types', function (Blueprint $table) {
            $table->dropColumn(['tba_fee','tba_fee_type','tbu_fee','tbu_fee_type','cft_fee','cft_fee_type','owt_fee','owt_fee_type','iwt_fee','iwt_fee_type','payment_fee','payment_fee_type']);
        });
    }
}

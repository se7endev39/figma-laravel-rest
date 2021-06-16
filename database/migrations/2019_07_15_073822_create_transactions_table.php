<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('user_id');  
            $table->decimal('amount',10,2);
			$table->bigInteger('account_id');
			$table->string('dr_cr',2);  
			$table->string('type',20);  
			$table->string('status',20);  
			$table->text('note')->nullable();   
			$table->integer('ref_id')->nullable();  
			$table->bigInteger('parent_id')->nullable();  
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
        Schema::dropIfExists('transactions');
    }
}

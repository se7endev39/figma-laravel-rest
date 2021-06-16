<?php

use Illuminate\Database\Seeder;

class UtilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Default Settings
		DB::table('settings')->insert([
			[
			  'name' => 'mail_type',
			  'value' => 'mail'
			],			
		]);
	
		
		//Store Default Currency
		DB::table('currency')->insert([
			[ 'id' => 1, 'name' => 'USD', 'base_currency' => 1, 'exchange_rate' => 1.10, 'status' => 1 ],
			[ 'id' => 2, 'name' => 'EUR', 'base_currency' => 0, 'exchange_rate' => 1.00, 'status' => 1 ],
			[ 'id' => 3, 'name' => 'GBP', 'base_currency' => 0, 'exchange_rate' => 0.89, 'status' => 1 ],
		]);
		
    }
}

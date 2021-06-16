<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['install']], function () {	
	
	//Frontend Route
	Route::get('/', function () {
		return redirect('login');
	});
	
	Auth::routes(['verify' => true]);
	
	Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

	//Pay Now Screen
	Route::get('payment_request/{id}','PaymentRequestController@view_payment_request');
	
	Route::group(['middleware' => ['auth','verified']], function () {
		
		Route::get('/dashboard', 'DashboardController@index');
        
		//Profile Controller
		Route::get('profile/edit', 'ProfileController@edit');
		Route::post('profile/update', 'ProfileController@update');
		Route::get('profile/change_password', 'ProfileController@change_password');
		Route::post('profile/update_password', 'ProfileController@update_password');

		//Messaging Route
		Route::get('message/inbox', 'MessageController@inbox');
		Route::get('message/outbox', 'MessageController@outbox');
		Route::get('message/compose', 'MessageController@compose');
		Route::get('message/view_inbox/{id}', 'MessageController@view_inbox');
		Route::get('message/view_outbox/{id}', 'MessageController@view_outbox');
		Route::get('message/remove/{id}', 'MessageController@remove');
		Route::post('message/send', 'MessageController@send')->name('messages.store');
		Route::post('message/reply_message', 'MessageController@reply_message')->name('messages.reply_message');
		Route::post('message/bulk_action','MessageController@bulk_action');

		//Gift Card Controller
		Route::get('gift_cards/status/{status}','GiftCardController@index');
		Route::resource('gift_cards','GiftCardController')->except([
		    'edit', 'update'
		]);

        //Get Account By User ID
		Route::get('admin/accounts/get_by_user_id/{user_id}','AccountController@get_by_user_id');
			
		//Get Account By Account Type
		Route::get('admin/accounts/get_by_account_type/{account_type}','AccountController@get_by_account_type');
		
		
		//Admin Only Routes
		Route::group(['middleware' => ['permission:admin,manager'], 'prefix'=> 'admin'], function () {
			
			//User Controller
			Route::get('users/status/{account_status}','UserController@index');
			Route::get('users/documents/{user_id}','UserController@view_documents');
			Route::get('users/documents','UserController@documents');
			Route::get('users/varify/{user_id}','UserController@varify');
			Route::get('users/unvarify/{user_id}','UserController@unvarify');
			Route::resource('users','UserController');

			//Transfer Request
			Route::get('transfer_request/{status?}/{id?}','TransferController@transfer_request');
			Route::get('transfer/action/{id}/{action}','TransferController@action');

			//Account Controller
			Route::resource('accounts','AccountController');

			//Card Controller
			Route::resource('cards','CardController');

			//Card Transactions
			Route::resource('card_transactions','CardTransactionController');
			
			//Deposit Controller
			Route::get('deposit/request/{status?}/{id?}/{action?}','DepositController@deposit_request');
			Route::resource('deposit','DepositController');
			
			//Withdraw Controller
			Route::resource('withdraw','WithdrawController');
			
			//Fee Controller
			Route::get('custom_fees/get_table_data','FeeController@get_table_data');
			Route::resource('custom_fees','FeeController');

			//Chart Of Account Controller
			Route::resource('category','ChartOfAccountController');

            //Income Controller
			Route::get('income/get_table_data','IncomeController@get_table_data');
			Route::resource('income','IncomeController');

			//Expense Controller
			Route::get('expense/get_table_data','ExpenseController@get_table_data');
			Route::resource('expense','ExpenseController');

			//Loan Product Controller
			Route::resource('loan_products','LoanProductController');

			//Loan Controller
			Route::get('loans/get_table_data','LoanController@get_table_data');
			Route::get('loans/calculator','LoanController@calculator')->name('loans.calculator');
			Route::post('loans/calculator/calculate','LoanController@calculate')->name('loans.calculate');
			Route::get('loans/approve/{id}','LoanController@approve')->name('loans.approve');
			Route::resource('loans','LoanController');
			
			//Loan Collateral Controller
			Route::get('loan_collaterals/loan/{loan_id}','LoanCollateralController@index')->name('loan_collaterals.index');
			Route::resource('loan_collaterals','LoanCollateralController');

			//Loan Payment Controller
			Route::get('loan_payments/get_repayment_by_loan_id/{loan_id}','LoanPaymentController@get_repayment_by_loan_id');
			Route::get('loan_payments/get_table_data','LoanPaymentController@get_table_data');
			Route::resource('loan_payments','LoanPaymentController');


			//Report Controller
			Route::match(['get', 'post'],'reports/account_statement/{view?}', 'ReportController@account_statement');
			Route::match(['get', 'post'],'reports/transactions_report/{view?}', 'ReportController@transactions_report');
			Route::match(['get', 'post'],'reports/deposit_report/{view?}', 'ReportController@deposit_report');
			Route::match(['get', 'post'],'reports/withdraw_report/{view?}', 'ReportController@withdraw_report');
			Route::match(['get', 'post'],'reports/wire_transfer_report/{view?}', 'ReportController@wire_transfer_report');
			Route::match(['get', 'post'],'reports/profit_and_loss_report/{view?}', 'ReportController@profit_and_loss_report');
			Route::match(['get', 'post'],'reports/profit_report_by_user/{view?}', 'ReportController@profit_report_by_user');

		});

        //Admin Only Route
		Route::group(['middleware' => ['admin'], 'prefix'=> 'admin'], function () {
			
			//Staff Controller
			Route::resource('staffs','StaffController');
				
			//Account Type Controller
			Route::resource('account_types','AccountTypeController');

			//Card Type Controller
			Route::resource('card_types','CardTypeController');

			//Currency Controller
			Route::resource('currency','CurrencyController');

			//Language Controller
			Route::resource('languages','LanguageController');	

			//Custom Field Section Controller
			Route::resource('custom_field_sections','CFSectionController');

			//Custom Filed Controller
			Route::resource('custom_fields','CustomFieldController');
		
			//Utility Controller
			Route::match(['get', 'post'],'administration/general_settings/{store?}', 'UtilityController@settings')->name('general_settings.update');
			Route::post('administration/upload_logo', 'UtilityController@upload_logo')->name('general_settings.update_logo');
			Route::get('administration/backup_database', 'UtilityController@backup_database')->name('utility.backup_database');
			Route::get('administration/message_template', 'UtilityController@message_template')->name('utility.message_template');

		});
		
		//User Only Route
		Route::group(['middleware' => ['user'], 'prefix'=> 'user'], function () {

			//Client Overview
			Route::get('overview','ClientController@overview');
			
			//Submit Documents
			Route::match(['get', 'post'], 'submit_documents', 'ClientController@submit_documents');

			//Pay Now
			Route::match(['get', 'post'],'payment_request/pay/{id}','PaymentRequestController@pay');

			//Payment Request
			Route::resource('payment_requests','PaymentRequestController')->except([
			    'edit', 'update'
			]);

			//Redeem Gift Card
			Route::match(['get', 'post'],'gift_cards/redeem','GiftCardController@redeem');
						
            //Transfer Between Accounts
			Route::match(['get', 'post'], 'transfer_between_accounts', 'ClientController@transfer_between_accounts');

			//Transfer Between Users
			Route::match(['get', 'post'], 'transfer_between_users', 'ClientController@transfer_between_users');

			//Card Funding Transfer
			Route::match(['get', 'post'], 'card_funding_transfer', 'ClientController@card_funding_transfer');

			//Outgoing Wire Transfer
			Route::match(['get', 'post'], 'outgoing_wire_transfer', 'ClientController@outgoing_wire_transfer');

			//View Account Details
			Route::get('accounts/{id}','ClientController@view_account_details');

			//View Transaction Details
			Route::get('view_transaction/{id}','ClientController@view_transaction');

			//My Loans
			Route::get('my_loans', 'ClientController@my_loans');
			Route::match(['get', 'post'], 'loans/apply_loan','ClientController@apply_loan');
			Route::get('loans/{loan_id}', 'ClientController@view_loan_details');
			Route::match(['get', 'post'], 'loans/payment/{loan_id}','ClientController@loan_payment');

			/** PayPal Payment Gateway **/
			Route::match(['get', 'post'], 'load_money/paypal', 'LoadMoneyController@paypal');
			Route::get('load_money/paypal_payment_authorize/{order_id}/{credit_account}','LoadMoneyController@paypal_payment_authorize');

			/** Stripe Payment Gateway **/
			Route::match(['get', 'post'], 'load_money/stripe', 'LoadMoneyController@stripe');
			Route::post('load_money/stripe_authorization', 'LoadMoneyController@stripe_authorization');

			/** BlockChain Payment Gateway **/
			Route::match(['get', 'post'], 'load_money/blockchain', 'LoadMoneyController@blockchain');

			/** Wire Transfer Payment Gateway **/
			Route::match(['get', 'post'], 'load_money/wire_transfer', 'LoadMoneyController@wire_transfer');
			Route::get('load_money/wire_transfer_details/{id}', 'LoadMoneyController@wire_transfer_details');
			

			//Client Report Controller
			Route::match(['get', 'post'],'reports/account_statement/{view?}', 'ClientReportController@account_statement');
			Route::match(['get', 'post'],'reports/all_transaction/{view?}', 'ClientReportController@all_transaction');
			Route::get('reports/referred_users', 'ClientReportController@referred_users');

			//Referral Link
			Route::get('profile/referral_link', 'ProfileController@referral_link');

			//Referral Commission
			Route::get('referral_commissions', 'ClientController@referral_commissions');
			Route::post('transfer_referral_commissions', 'ClientController@transfer_referral_commissions');
			
			//Merchant API
			Route::get('merchant_api', 'ClientController@merchant_api');
			
		});
		
		//Checkout Page
		Route::match(['get', 'post'],'checkout','CheckoutController@checkout')->name('checkout');
		Route::get('checkout/success','CheckoutController@success')->name('checkout.success');

	});
	
});


//JSON data for dashboard chart
Route::get('dashboard/json_month_wise_deposit','DashboardController@json_month_wise_deposit')->middleware('auth');
Route::get('dashboard/json_month_wise_withdraw','DashboardController@json_month_wise_withdraw')->middleware('auth');

//BlockChain IPN
Route::get('load_money/blockchain_ipn', 'LoadMoneyController@blockchain_ipn');

//Update System
Route::get('migration/update', 'Install\UpdateController@update_migration');


Route::get('/installation', 'Install\InstallController@index');
Route::get('install/database', 'Install\InstallController@database');
Route::post('install/process_install', 'Install\InstallController@process_install');
Route::get('install/create_user', 'Install\InstallController@create_user');
Route::post('install/store_user', 'Install\InstallController@store_user');
Route::get('install/system_settings', 'Install\InstallController@system_settings');
Route::post('install/finish', 'Install\InstallController@final_touch');
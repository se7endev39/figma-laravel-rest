<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'auth'], function ($router) {
	Route::post('register', 'API\AuthController@register')->middleware('cors');
	Route::post('login', 'API\AuthController@login');
	Route::post('logout', 'API\AuthController@logout');
	Route::post('refresh', 'API\AuthController@refresh');
	Route::post('profile', 'API\AuthController@profile');
	Route::post('update_profile', 'API\AuthController@update_profile');
	Route::post('update_password', 'API\AuthController@update_password');
	Route::post('update_profile_picture', 'API\AuthController@update_profile_picture');
});


Route::group(['prefix' => 'v1', 'middleware' => 'jwt.auth'], function ($router) {
	
	//Cards & Account Route
	Route::get('accounts_cards','API\v1\AccountController@accounts_cards');
	Route::get('cards','API\v1\AccountController@cards');
	Route::get('currency_list','API\v1\AccountController@currency_list');
	Route::resource('accounts','API\v1\AccountController')->only(['index','show']);
    
	//Payment Request Route
	Route::resource('payment_requests','API\v1\PaymentRequestController')->except(['edit','update']);
    
	//Transfer Route
	Route::post('transfer/transfer_between_accounts','API\v1\TransferController@transfer_between_accounts');
	Route::post('transfer/transfer_between_users','API\v1\TransferController@transfer_between_users');
	Route::post('transfer/card_funding_transfer','API\v1\TransferController@card_funding_transfer');
	Route::post('transfer/outgoing_wire_transfer','API\v1\TransferController@outgoing_wire_transfer');
    
	//Gift Card Route
	Route::post('gift_cards/redeem','API\v1\GiftCardController@redeem');				
	Route::resource('gift_cards','API\v1\GiftCardController')->except(['edit','update']);
 
	
	//Messaging Route
	Route::get('message/receiver_list', 'API\v1\MessageController@receiver_list');
	Route::get('message/inbox', 'API\v1\MessageController@inbox');
	Route::get('message/outbox', 'API\v1\MessageController@outbox');
	Route::get('message/view_inbox/{id}', 'API\v1\MessageController@view_inbox');
	Route::get('message/view_outbox/{id}', 'API\v1\MessageController@view_outbox');
	Route::get('message/remove/{id}', 'API\v1\MessageController@remove');
	Route::post('message/send', 'API\v1\MessageController@send');
	Route::post('message/reply_message', 'API\v1\MessageController@reply_message');
	Route::post('message/bulk_action','API\v1\MessageController@bulk_action');
	
	//Report Controller
	Route::get('reports/transactions','API\v1\ReportController@transactions');
	
});
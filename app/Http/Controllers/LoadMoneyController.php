<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Sample\PayPalClient;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Stripe;
use App\User;
use App\Deposit;
use App\Transaction;
use App\BlockChainInvoice;
use App\WireDepositRequest;
use Hash;
use Auth;
use DB;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class LoadMoneyController extends Controller
{
    
   
    /** PayPal Payment Gateway **/
    public function paypal(Request $request)
    {	
    	if(get_option('paypal_active') == 'No'){
            return redirect('dashboard');
    	}

		$data = array();
		$data['method'] = '';
		$data['credit_account'] = '';
		$data['amount'] = '';
		
		if($request->isMethod('post')){
			$data['method'] = 'PayPal';
			$data['credit_account'] = $request->credit_account;
			$data['amount'] = $request->amount;
			$data['converted_amount'] = convert_currency(account_currency($request->credit_account),'USD',$request->amount);
			$charge_percent = get_option('paypal_deposit_charge', 0);

			if($charge_percent > 0){
               $charge = ($charge_percent / 100) * $data['converted_amount'];
               $data['converted_amount'] = $data['converted_amount'] + $charge;
			}
			
			return view('backend.user_panel.load_money.paypal',$data);
		}else{
			return view('backend.user_panel.load_money.paypal',$data);
		}
	}
		
	public function paypal_payment_authorize($orderId, $credit_account){
		@ini_set('max_execution_time', 0);
	    @set_time_limit(0);

		// Creating an environment
		$clientId = get_option('paypal_client_id');
		$clientSecret = get_option('paypal_secret');

		if(get_option('paypal_mode') != 'production'){
			$environment = new SandboxEnvironment($clientId, $clientSecret);
		}else{
			$environment = new ProductionEnvironment($clientId, $clientSecret);
		}
			
		$client = new PayPalHttpClient($environment);

		$request = new OrdersCaptureRequest($orderId);
		$request->prefer('return=representation');

		try {
		    // Call API with your client and get a response for your call
		    $response = $client->execute($request);
		    //dd($response);
		    
		    if($response->result->status == 'COMPLETED'){

		    	$currency = $response->result->purchase_units[0]->amount->currency_code;
		    	$amount = $response->result->purchase_units[0]->amount->value;

		    	$charge_percent = get_option('paypal_deposit_charge', 0);
		    	if($charge_percent > 0){
                   $charge = ($charge_percent / 100) * $amount;
                   $amount = $amount - $charge;
				}
		    	

                DB::beginTransaction();

		        $deposit = new Deposit();
			    $deposit->method = 'PayPal';
			    $deposit->type = 'deposit';
				$deposit->amount = convert_currency($currency, account_currency($credit_account), $amount);
				$deposit->account_id = $credit_account;
				$deposit->note = _lang('Deposit Via PayPal');
				$deposit->status = 1;
				$deposit->user_id = Auth::id();
			
		        $deposit->save();
				
				//Create Transaction
				$transaction = new Transaction();
			    $transaction->user_id = $deposit->user_id;
				$transaction->amount = $deposit->amount;
				$transaction->account_id = $deposit->account_id;
				$transaction->dr_cr = 'cr';
				$transaction->type = $deposit->type;
				$transaction->status = 'complete';
				$transaction->note = $deposit->note;
				$transaction->ref_id = $deposit->id;
				$transaction->created_by = $deposit->user_id;
				$transaction->updated_by = $deposit->user_id;
			
		        $transaction->save();
				
				//Send Message Notification
				/*$message_object = new \stdClass();
				$message_object->first_name = $transaction->user->first_name;
				$message_object->last_name = $transaction->user->last_name;
				$message_object->account = $transaction->account->account_number;
				$message_object->currency = $transaction->account->account_type->currency->name;
				$message_object->amount = $transaction->amount;
				$message_object->date = $transaction->created_at->toDateTimeString();
				
				send_message($deposit->user_id, get_option('deposit_subject'), get_option('deposit_message'), $message_object);
				*/
				
				//Registering Event
				event(new \App\Events\DepositMoney($transaction));
					
				DB::commit();
				
				return redirect('dashboard')->with('success', _lang('Deposit made sucessfully'));
			}
		    
		}catch (HttpException $ex) {
		    //echo $ex->statusCode;
		    //print_r($ex->getMessage());
		    return back()->with('error', _lang('Sorry, Deposit not completed, Please contact with your administrator !'));
		}

	}
	
	/** End PayPal Payment Gateway **/


	/** Stripe Payment Gateway **/
    public function stripe(Request $request)
    {	
    	if(get_option('stripe_active') == 'No'){
            return redirect('dashboard');
    	}

		$data = array();
		$data['method'] = '';
		$data['credit_account'] = '';
		$data['amount'] = '';
		
		if($request->isMethod('post')){
			$data['method'] = 'Stripe';
			$data['credit_account'] = $request->credit_account;
			$data['amount'] = $request->amount;
			$data['converted_amount'] = convert_currency(account_currency($request->credit_account),'USD',$request->amount);
			$charge_percent = get_option('stripe_deposit_charge', 0);

			if($charge_percent > 0){
               $charge = ($charge_percent / 100) * $data['converted_amount'];
               $data['converted_amount'] = $data['converted_amount'] + $charge;
			}
			
			return view('backend.user_panel.load_money.stripe',$data);
		}else{
			return view('backend.user_panel.load_money.stripe',$data);
		}
	}


	public function stripe_authorization(Request $request){
		@ini_set('max_execution_time', 0);
		@set_time_limit(0);

		$payable_amount = $request->payable_amount;
		$credit_account = $request->credit_account;

		Stripe\Stripe::setApiKey(get_option('stripe_secret_key'));
		$charge = Stripe\Charge::create ([
			"amount" => $payable_amount * 100,
			"currency" => "USD",
			"source" => $request->stripeToken,
			"description" => _lang('Deposit Via Stripe'),
		]);

		// Retrieve Charge Details 
    	if($charge->amount_refunded == 0 && $charge->failure_code == null && $charge->paid == true && $charge->captured == true){ 

            $currency = $charge->currency;
	    	$amount = $charge->amount / 100;

	    	$charge_percent = get_option('stripe_deposit_charge', 0);
	    	if($charge_percent > 0){
               $charge = ($charge_percent / 100) * $amount;
               $amount = $amount - $charge;
			}
	    	
            DB::beginTransaction();

	        $deposit = new Deposit();
		    $deposit->method = 'Stripe';
		    $deposit->type = 'deposit';
			$deposit->amount = convert_currency($currency, account_currency($credit_account), $amount);
			$deposit->account_id = $credit_account;
			$deposit->note = _lang('Deposit Via Stripe');
			$deposit->status = 1;
			$deposit->user_id = Auth::id();
		
	        $deposit->save();
			
			//Create Transaction
			$transaction = new Transaction();
		    $transaction->user_id = $deposit->user_id;
			$transaction->amount = $deposit->amount;
			$transaction->account_id = $deposit->account_id;
			$transaction->dr_cr = 'cr';
			$transaction->type = $deposit->type;
			$transaction->status = 'complete';
			$transaction->note = $deposit->note;
			$transaction->ref_id = $deposit->id;
			$transaction->created_by = $deposit->user_id;
			$transaction->updated_by = $deposit->user_id;
		
	        $transaction->save();
			
			//Send Message Notification
			/*$message_object = new \stdClass();
			$message_object->first_name = $transaction->user->first_name;
			$message_object->last_name = $transaction->user->last_name;
			$message_object->account = $transaction->account->account_number;
			$message_object->currency = $transaction->account->account_type->currency->name;
			$message_object->amount = $transaction->amount;
			$message_object->date = $transaction->created_at->toDateTimeString();
			
			send_message($deposit->user_id, get_option('deposit_subject'), get_option('deposit_message'), $message_object);
			*/
			
			//Registering Event
			event(new \App\Events\DepositMoney($transaction));
				
			DB::commit();
			
			return redirect('dashboard')->with('success', _lang('Deposit made sucessfully'));
    	}else{
    		return back()->with('error', _lang('Sorry, Deposit not completed, Please try again !'));
    	}	

	}
	/** End Stripe Payment Gateway **/


	/** BlockChain Payment Gateway **/
    public function blockchain(Request $request)
    {	
    	if(get_option('blockchain_active') == 'No'){
            return redirect('dashboard');
    	}

		$data = array();
		$data['method'] = '';
		$data['credit_account'] = '';
		$data['amount'] = '';
		
		if($request->isMethod('post')){

			$data['method'] = 'BlockChain';
			$data['credit_account'] = $request->credit_account;
			$data['amount'] = $request->amount;
			

			$charge_percent = get_option('blockchain_deposit_charge', 0);

			if($charge_percent > 0){
               $charge = ($charge_percent / 100) * $data['amount'];
               $data['amount'] = $data['amount'] + $charge;
			}
			
			//Convert to BTC Amount
			$btc_amount = file_get_contents("https://blockchain.info/tobtc?currency=USD&value={$data['amount']}");
			$data['converted_amount'] = $btc_amount;


			//Block Chain Implementation
			$secret = "KJSHYdsf7hg65dssA";

			$xpub = get_option('blockchain_xpub');
			$api_key = get_option('blockchain_api_key');

            
            $invoice_id = 'BC'.Auth::id().rand(10000, 99999);
			
			$site_url = url('/');

			$callback_url = $site_url . "/load_money/blockchain_ipn?invoice_id=" . $invoice_id . "&secret=" . $secret;

			$root_url = 'https://api.blockchain.info/v2/receive';

			//$gap_limit = 1000;

			$parameters = "key=" .$api_key. "&callback=" .urlencode($callback_url) . "&xpub=" . $xpub;
			
			$out = new \Symfony\Component\Console\Output\ConsoleOutput();
			$out->writeln($root_url . '?' . $parameters);


			$resp = @file_get_contents($root_url . '?' . $parameters);

			if(!$resp){
				return back()->with('error', _lang('BlockChain API Having Issue. Please Try Again Later'));
			}
			
			$response = json_decode($resp);

			//Store Unpaid Invoice
            $bc_invoice = new BlockChainInvoice();
            $bc_invoice->invoice_id = $invoice_id;
            $bc_invoice->user_id = Auth::id();
            $bc_invoice->credit_account = $request->credit_account;
            $bc_invoice->amount = $request->amount;
            $bc_invoice->btc_address = $response->address;
            $bc_invoice->btc_amount = $btc_amount;
            $bc_invoice->status = 0;
            $bc_invoice->save();

			$qr_code = "bitcoin:".$response->address."?amount=".$data['converted_amount'];
			$data['btc_address'] = $response->address;
		    $data['qr_code'] =  "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$qr_code&choe=UTF-";
		    //$data['qr_code'] =  "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=bitcoin:$qr_code";
			
			return view('backend.user_panel.load_money.blockchain',$data);
		}else{
			return view('backend.user_panel.load_money.blockchain',$data);
		}
	}

	public function blockchain_ipn(){
        $invoice_id = $_GET['invoice_id'];
        $secret = $_GET['secret'];
        $address = $_GET['address'];
        $value = $_GET['value'];
        $confirmations = $_GET['confirmations'];
        $value_in_btc = $value / 100000000;

        $my_secret = "KJSHYdsf7hg65dssA";
    
        $trx_hash = $_GET['transaction_hash'];
    
        $bc_invoice = BlockChainInvoice::where('invoice_id',$invoice_id)
									   ->where('btc_address',$address)
									   ->where('status',0)
									   ->first();
    
    
        if ($bc_invoice) {
            if($value_in_btc >= $bc_invoice->btc_amount && $secret == $my_secret && $confirmations > 2 ){
                //Update Invoice
                $bc_invoice->status = 1;
                $bc_invoice->save();

                //Create Deposit
                DB::beginTransaction();

		        $deposit = new Deposit();
			    $deposit->method = 'BlockChain';
			    $deposit->type = 'deposit';
				$deposit->amount = $bc_invoice->amount;
				$deposit->account_id = $bc_invoice->credit_account;
				$deposit->note = _lang('Deposit Via BlockChain');
				$deposit->status = 1;
				$deposit->user_id = $bc_invoice->user_id;
			
		        $deposit->save();
				
				//Create Transaction
				$transaction = new Transaction();
			    $transaction->user_id = $deposit->user_id;
				$transaction->amount = $deposit->amount;
				$transaction->account_id = $deposit->account_id;
				$transaction->dr_cr = 'cr';
				$transaction->type = $deposit->type;
				$transaction->status = 'complete';
				$transaction->note = $deposit->note;
				$transaction->ref_id = $deposit->id;
				$transaction->created_by = $deposit->user_id;
				$transaction->updated_by = $deposit->user_id;
			
		        $transaction->save();
				
				//Send Message Notification
				/*$message_object = new \stdClass();
				$message_object->first_name = $transaction->user->first_name;
				$message_object->last_name = $transaction->user->last_name;
				$message_object->account = $transaction->account->account_number;
				$message_object->currency = $transaction->account->account_type->currency->name;
				$message_object->amount = $transaction->amount;
				$message_object->date = $transaction->created_at->toDateTimeString();
				
				send_message($deposit->user_id, get_option('deposit_subject'), get_option('deposit_message'), $message_object);
				*/
				
				//Registering Event
				event(new \App\Events\DepositMoney($transaction));
				
				DB::commit();
			
            }
        }

	}

	/** End BlockChain Payment Gateway **/


	/** Wire Transfer Deposit **/
    public function wire_transfer(Request $request)
    {	
    	if(get_option('wire_transfer_withdraw_active') == 'No'){
            return redirect('dashboard');
    	}

		$data = array();
		$data['method'] = '';
		$data['credit_account'] = '';
		$data['amount'] = '';
		
		if($request->isMethod('post')){

			$transaction_id = 'TNX0000'.get_next_id('wire_deposit_requests');

			$deposit_request = new WireDepositRequest();
			$deposit_request->transaction_id = $transaction_id;
			$deposit_request->user_id = Auth::id();
			$deposit_request->credit_account = $request->credit_account;
			$deposit_request->amount = $request->amount;

			$charge_percent = get_option('wire_deposit_charge', 0);

			if($charge_percent > 0){
	           $charge = ($charge_percent / 100) * $request->amount;
	           $deposit_request->charge = $charge;
			}else{
			   $deposit_request->charge = 0;
			}

			$deposit_request->status = 'pending';
			$deposit_request->save();
			
			return redirect('user/load_money/wire_transfer_details/'.$deposit_request->id)
			       ->with('success',_lang('Your Order No').' '. $transaction_id .' '._lang('has placed sucessfully'));
		}else{
			return view('backend.user_panel.load_money.wire_transfer', $data);
		}
	}

	public function wire_transfer_details($id){
		$data = array();
		$data['method'] = 'WireTransfer';
        $deposit_request = WireDepositRequest::where('id',$id)
                                             ->where('user_id',Auth::id())
                                             ->first();
        if(! $deposit_request){
           abort(401);
        }   

        $data['deposit_request'] = $deposit_request;

        return view('backend.user_panel.load_money.wire_transfer', $data);                                 

	}
	/** End Wire Transfer Deposit **/

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Validator;
use App\Utilities\Overrider;
use App\Notifications\PaymentRequest as RequestNotification;
use Auth;
use DB;

class CheckoutController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
    }
	
	
	public function checkout(Request $request){
		if ($request->isMethod('get')){
			$validator = Validator::make($request->all(), [
				'business_email' => 'required',
				'item_number' 	 => 'required',
				'item_name' 	 => 'required',
				'account_number' => 'required',
				'amount' 		 => 'required|numeric',
				'success_url' 	 => 'required',
				'cancel_url' 	 => 'required',
			]);
			
			if ($validator->fails()) {
				$data = array();
				$data['validation_errors'] = $validator->errors();
				return view('backend.user_panel.checkout.validation_error',$data);				
			}
			
			//Check account is valid
			$account = \App\Account::where('account_number',$request->account_number);
			if(! $account->first()){
				abort(403,_lang('Invalid Merchant Account Number !'));
			}
			
			//Set Data to session
			session([
				'business_email'  	=> $request->business_email,
				'success_url'  		=> $request->success_url,
				'cancel_url'   		=> $request->cancel_url,
				'notify_url'   		=> $request->notify_url,
				'account_number'  	=> $request->account_number,
				'amount'       		=> $request->amount,
				'item_number'  		=> $request->item_number,
				'item_name'    		=> $request->item_name,
				'item_description'  => $request->item_description,
				'custom1'  	   		=> $request->custom1,
				'custom2'      		=> $request->custom2,
				'paid'      		=> 0,
			]);
			
			return view('backend.user_panel.checkout.pay');
		}else if($request->isMethod('post')){
			         	
			$validator = Validator::make($request->all(), [
				'debit_account' => 'required',
			]);
			
			if ($validator->fails()) {
				if($request->ajax()){ 
					return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
				}else{
					return back()->withErrors($validator)
								 ->withInput();
				}			
			}
			
			DB::beginTransaction();
			
		    $account = \App\Account::where('account_number', session('account_number'))->first();
			
			if(Auth::user()->email == session('business_email')){
				return back()->with('error', _lang('This Payment Request made by you. You cannot pay against your own payment request !'));
			}
			
			$debit_account = \App\Account::where('id', $request->debit_account)
													->where('user_id',Auth::id());
			if(! $debit_account->first()){
				return back()->with('error', _lang('Sorry Your Account is not valid !'));
			}										
			
			$amount = convert_currency(account_currency($account->id), account_currency($request->debit_account), session('amount'));
			

			//Generate Fee 
			$fee = generate_fee( $amount, $debit_account->account_type->payment_fee, $debit_account->account_type->payment_fee_type );
			//$fee = 0;
			
			//Check available Balance
			if(get_account_balance($request->debit_account) < ($amount + $fee)){
				return back()->with('error', _lang('Insufficient balance !'));
			}

			/* Status will only apply on credit account */
			$status = 'complete';
			
			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = Auth::id();
			$debit->amount = $amount;
			$debit->account_id = $request->debit_account;
			$debit->dr_cr = 'dr';
			$debit->type = 'payment';
			$debit->status = $status;
			$debit->note = 'Item Number: '.session('item_number').', Item Name: '.session('item_name');
			$debit->created_by = Auth::id();
			$debit->updated_by = Auth::id();
			$debit->save();
			
			//Make fee Transaction
			if($fee > 0) {
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $fee;
				$fee_debit->account_id = $request->debit_account;
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = $status;
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = 'Item Number: '.session('item_number').', Item Name: '.session('item_name');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			//Make Credit Transaction
			$credit = new Transaction();
			$credit->user_id = $account->user_id;
			$credit->account_id = $account->id;
			$credit->amount = session('amount');
			$credit->dr_cr = 'cr';
			$credit->type = 'payment';
			$credit->status = $status;
			$credit->parent_id = $debit->id;
			$credit->note = 'Item Number: '.session('item_number').', Item Name: '.session('item_name');
			$credit->created_by = Auth::id();
			$credit->updated_by = Auth::id();
			$credit->save();
			
			// Send Confrimation Email/SMS
            $message_object = new \stdClass();
            $message_object->first_name = $credit->user->first_name;
            $message_object->last_name = $credit->user->last_name;
            $message_object->payer = $debit->user->first_name.' '.$debit->user->last_name;
            $message_object->account = $credit->account->account_number;
            $message_object->currency = $credit->account->account_type->currency->name;
            $message_object->amount = $credit->amount;
            $message_object->date = $credit->created_at->toDateTimeString();

            send_message($credit->user_id, get_option('payment_received_subject'), get_option('payment_received_message'), $message_object);
			
			DB::commit();
			
			if($credit->id > 0){
				//Send IPN Notifications
				if(session('notify_url') != ''){
					$data = [
						'payer_first_name' 		=> $debit->user->first_name,
						'payer_last_name'  		=> $debit->user->last_name,
						'payer_email' 	   		=> $debit->user->email,
						'amount' 		   		=> session('amount'),
						'currency' 		   		=> account_currency($account->id),
						'item_number' 	   		=> session('item_number'),
						'item_name' 	   		=> session('item_name'),
						'item_description'  	=> session('item_description'),
						'custom1'  				=> session('custom1'),
						'custom2'  				=> session('custom2'),
					];
					
					// Create curl request
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, session('notify_url'));
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					/*curl_setopt($curl, CURLOPT_HTTPHEADER,[
						'Content-Type: application/json'
					]);*/
					$response = curl_exec($curl);
					curl_close($curl);

					//$responseData = json_decode($response);
				}
				
				session(['paid' => 1]);
				
				return redirect()->route('checkout.success')->with('success', _lang('Thank You, Your Payment Was Made Sucessfully'));
		    }else{
		    	return back()->with('error', _lang('Error Occured, Please try again !'));
		    }
			
		}
	}
	
	public function success(){
		if(session('paid') == 1){
			return view('backend.user_panel.checkout.success');
		}
		abort(403);	
	}
}
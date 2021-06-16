<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PaymentRequest;
use App\Transaction;
use App\Account;
use Validator;
use App\Utilities\Overrider;
use Illuminate\Validation\Rule;
use App\Notifications\PaymentRequest as RequestNotification;
use Auth;
use DB;

class PaymentRequestController extends Controller
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
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentrequests = PaymentRequest::where('created_by',Auth::id())
		                                 ->orderBy('id','desc')
										 ->get();
        return view('backend.user_panel.payment_request.list',compact('paymentrequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.user_panel.payment_request.create');
		}else{
           return view('backend.user_panel.payment_request.modal.create');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
		$validator = Validator::make($request->all(), [
			'account_id' 	   => 'required',
			'amount' 		   => 'required|numeric',
			'recipients_email' => 'required',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('payment_requests.create')
							->withErrors($validator)
							->withInput();
			}			
		}
			    
		
        $paymentrequest = new PaymentRequest();
	    $paymentrequest->account_id = $request->input('account_id');
		$paymentrequest->amount = $request->input('amount');
		$paymentrequest->status = 'pending';
		$paymentrequest->description = $request->input('description');
		$paymentrequest->created_by = Auth::id();
	
        $paymentrequest->save();
		
		//Send Email Notification
		Overrider::load("Settings");
		
		$user = new \App\User();
		$user->email = $request->recipients_email;
		$user->notify(new RequestNotification($paymentrequest));

		if(! $request->ajax()){
           return redirect()->route('payment_requests.create')->with('success', _lang('Payment Request Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Payment Request Sucessfully'),'data'=>$paymentrequest]);
		}
        
   }
	

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $paymentrequest = PaymentRequest::where('id',$id)
		                                ->where('created_by',Auth::id())
										->first();
		if(! $request->ajax()){
		    return view('backend.user_panel.payment_request.view',compact('paymentrequest','id'));
		}else{
			return view('backend.user_panel.payment_request.modal.view',compact('paymentrequest','id'));
		} 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paymentrequest = PaymentRequest::where('id',$id)
		                                ->where('created_by',Auth::id());
        $paymentrequest->delete();
        return redirect()->route('payment_requests.index')->with('success',_lang('Deleted Sucessfully'));
    }
	
	/**
     * Show Pay Now Screen.
     *
     * @param  encrypted $id
     * @return \Illuminate\Http\Response
     */
	public function view_payment_request($id){
		$id = decrypt($id);
		$paymentrequest = PaymentRequest::find($id);
		return view('backend.user_panel.payment_request.public_view', compact('paymentrequest'));
	}
	
	public function pay(Request $request, $id){
		if (! $request->isMethod('post')){
			$id = decrypt($id);
		    $paymentrequest = PaymentRequest::find($id);

			//Show the Payment Form
			return view('backend.user_panel.payment_request.pay',compact('paymentrequest'));
		}else{	
			@ini_set('max_execution_time', 0);
			@set_time_limit(0);
			         	
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
			
		    $paymentrequest = PaymentRequest::find($id);
			
			if(Auth::id() == $paymentrequest->created_by){
				return back()->with('error', _lang('This Payment Request made by you. You cannot pay against your own payment request !'));
			}
			
			$account = Account::find($paymentrequest->account_id);
			
			$amount = convert_currency(account_currency($paymentrequest->account_id), account_currency($request->debit_account), $paymentrequest->amount);
			
			$fee = generate_fee( $amount, $account->account_type->tbu_fee, $account->account_type->tbu_fee_type );

			//Check available Balance
			if(get_account_balance($request->debit_account) < ($amount + $fee)){
				return back()->with('error', _lang('Insufficient balance !'));
			}

			/* Status will only apply on credit account */
			$status = 'complete';
			if(get_option('tbu_approval') == 'yes'){
				$status = 'pending';
			}
			
			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = Auth::id();
			$debit->amount = $amount;
			$debit->account_id = $request->debit_account;
			$debit->dr_cr = 'dr';
			$debit->type = 'payment';
			$debit->status = $status;
			$debit->note = _lang('Payment');
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
				$fee_debit->note = _lang('Payment Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			//Make Credit Transaction
			$credit = new Transaction();
			$credit->user_id = $paymentrequest->created_by;
			$credit->account_id = $paymentrequest->account_id;
			$credit->amount = $paymentrequest->amount;
			$credit->dr_cr = 'cr';
			$credit->type = 'payment';
			$credit->status = $status;
			$credit->parent_id = $debit->id;
			$credit->note = _lang('Payment Received');
			$credit->created_by = Auth::id();
			$credit->updated_by = Auth::id();
			$credit->save();

            $paymentrequest->status = 'processing';
            $paymentrequest->paid_by = Auth::id();
            $paymentrequest->transaction_id = $debit->id;
			$paymentrequest->save();

			// Send Confrimation Email/SMS
            /*$message_object = new \stdClass();
            $message_object->first_name = $credit->user->first_name;
            $message_object->last_name = $credit->user->last_name;
            $message_object->payer = $debit->user->first_name.' '.$debit->user->last_name;
            $message_object->account = $credit->account->account_number;
            $message_object->currency = $credit->account->account_type->currency->name;
            $message_object->amount = $credit->amount;
            $message_object->date = $credit->created_at->toDateTimeString();

            send_message($credit->user_id, get_option('payment_received_subject'), get_option('payment_received_message'), $message_object);
			*/
			
			//Registering Event
			event(new \App\Events\PaymentReceived($credit, $debit));
					
			DB::commit();
			
			if($credit->id > 0){
				if($status == 'complete'){
					return back()->with('success', _lang('Thank You, Your Payment Was Made Sucessfully'));
				}else{
					return back()->with('success', _lang('Thank You, Your Payment Was Made Sucessfully. You will be notfied shortly after reviewing by authority.'));
				}
		    }else{
		    	return back()->with('error', _lang('Error Occured, Please try again !'));
		    }
			
		}	
	}
}
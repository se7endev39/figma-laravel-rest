<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Transaction;
use App\CardTransaction;
use App\PaymentRequest;
use Auth;
use DB;

class TransferController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set(get_option('timezone'));
    }

    /**
     * Show list of transfer request.
     *
     * @return \Illuminate\Http\Response
     */
    public function transfer_request( $status = 'pending', $id = '' )
    {
        if($id == ''){
             if( $status != 'pending' && $status != 'reject' ){
                 abort(404);
             }
    	     $transactions = Transaction::where('status',$status)
                                        ->where('dr_cr','dr')
                                        ->whereRaw("(type = 'transfer' OR type = 'wire_transfer' OR type = 'card_transfer' OR type = 'payment')")
                                        ->orderBy('id','desc')
                                        ->get();
             return view('backend.transfer_request.list', compact('transactions','status'));
        }else{
           $transaction = Transaction::find($id);
           return view('backend.transfer_request.modal.view_transaction', compact('transaction','status')); 
        }
    }

    /* Transfer Request Action */
    public function action( $id, $action )
    {
        if($action == 'approve'){
             DB::beginTransaction();

             $transaction = Transaction::find($id);
             $credit_transaction = Transaction::where('parent_id',$id)
											  ->where('dr_cr','cr')
											  ->first();
 
             $debit = Transaction::where('id', $id)
                                ->orWhere('parent_id', $id)
                                ->update(['status' => 'complete']);  

             if($transaction->type == 'card_transfer'){
                  $card_transaction = $transaction->card_transfer;
                  $card_transaction->status = 1;
                  $card_transaction->save();
             }  
    
   
            // Send Confrimation Email/SMS
            /*$message_object = new \stdClass();
            $message_object->first_name = $transaction->user->first_name;
            $message_object->last_name = $transaction->user->last_name;
            $message_object->account = $transaction->account->account_number;
            $message_object->currency = $transaction->account->account_type->currency->name;
            $message_object->amount = $transaction->amount;
            $message_object->date = $transaction->created_at->toDateTimeString();

            send_message($transaction->user_id, get_option('request_approved_subject'), get_option('request_approved_message'), $message_object);
			*/
			
			//Registering Event
			event(new \App\Events\TransferRequestApproved($transaction));
			if($credit_transaction != null){
				event(new \App\Events\DepositMoney($credit_transaction));
			}
			 
            DB::commit();

            return back()->with('success', _lang('Transaction Approved'));

        }else if($action == 'reject'){
             DB::beginTransaction();

             $transaction = Transaction::find($id);

             //Refund Back to Debit Account
             $debit = Transaction::where('id', $id)
                                ->orWhere('parent_id', $id)
                                ->update(['status' => 'reject']);     

             if($transaction->type == 'payment'){
                  $payment_request = PaymentRequest::where('transaction_id',$id)->first();
                  $payment_request->status = 'rejected';
                  $payment_request->save();
             }       
			
			 if($transaction->type == 'card_transfer'){
                  $card_transaction = $transaction->card_transfer;
                  $card_transaction->status = 0;
                  $card_transaction->save();
             }  

             
            // Send Confrimation Email/SMS
            /*$message_object = new \stdClass();
            $message_object->first_name = $transaction->user->first_name;
            $message_object->last_name = $transaction->user->last_name;
            $message_object->account = $transaction->account->account_number;
            $message_object->currency = $transaction->account->account_type->currency->name;
            $message_object->amount = $transaction->amount;
            $message_object->date = $transaction->created_at->toDateTimeString();

            send_message($transaction->user_id, get_option('request_rejected_subject'), get_option('request_rejected_message'), $message_object);
			*/
			
			//Registering Event
			event(new \App\Events\TransferRequestRejected($transaction));
			 
			 
            DB::commit();

            return back()->with('success', _lang('Transaction Rejected'));
        }
    }


}

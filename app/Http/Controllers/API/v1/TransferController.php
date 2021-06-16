<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\Transaction;
use App\CardTransaction;
use App\Deposit;
use App\Withdraw;
use App\WireTransfer;
use Validator;
use DB;

class TransferController extends Controller
{
	
	public $successStatus = 200;
	public $errorStatus = 401;
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /** Transfer Between Accounts **/
    public function transfer_between_accounts(Request $request)
    {  	
		$validator = Validator::make($request->all(), [
			'amount'         => 'required|numeric',
			'debit_account'  => 'required',
			'credit_account' => 'required|different:debit_account',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
		
		$account = Account::find($request->debit_account);

		//Generate Fee 
		$fee = generate_fee( $request->amount, $account->account_type->tba_fee, $account->account_type->tba_fee_type );
			

		//Check available balance
		if(get_account_balance($request->debit_account) < ($request->amount + $fee)){
			return response()->json(['result' => false, 'message' => _lang('Insufficient balance !')]);
		}
		
		DB::beginTransaction();
		

		/* Status will only apply on credit account */
		$status = 'complete';
		if(get_option('tba_approval') == 'yes'){
			$status = 'pending';
		}
		
		$user_id = auth('api')->user()->id;
		
		//Make Debit Transaction
		$debit = new Transaction();
		$debit->user_id = $user_id;
		$debit->amount = $request->input('amount');
		$debit->account_id = $request->input('debit_account');
		$debit->dr_cr = 'dr';
		$debit->type = 'transfer';
		$debit->status = $status;
		$debit->note = $request->input('note');
		$debit->created_by = $user_id;
		$debit->updated_by = $user_id;
		$debit->save();

		//Make fee Transaction
		if($fee > 0) {
			$fee_debit = new Transaction();
			$fee_debit->user_id = $user_id;
			$fee_debit->amount = $fee;
			$fee_debit->account_id = $request->input('debit_account');
			$fee_debit->dr_cr = 'dr';
			$fee_debit->type = 'fee';
			$fee_debit->status = $status;
			$fee_debit->parent_id = $debit->id;
			$fee_debit->note = _lang('Transfer Between Account Fee');
			$fee_debit->created_by = $user_id;
			$fee_debit->updated_by = $user_id;
			$fee_debit->save();
		}
		
		//Make Credit Transaction
		$credit = new Transaction();
		$credit->user_id = $user_id;
		$credit->account_id = $request->input('credit_account');
		$credit->amount = convert_currency(account_currency( $debit->account_id ), account_currency($credit->account_id), $request->amount);
		$credit->dr_cr = 'cr';
		$credit->type = 'transfer';
		$credit->status = $status;
		$credit->parent_id = $debit->id;
		$credit->note = $request->input('note');
		$credit->created_by = $user_id;
		$credit->updated_by = $user_id;
		$credit->save();

		
		DB::commit();
		
		if($credit->id > 0){
			$data['result'] =  true;
			$data['data']= $credit;
			
			if($status == 'complete'){
				$data['message'] =  _lang('Money Transfer Sucessfully');
				return response()->json($data, $this->successStatus);
			}else{
				$data['message'] =  _lang('Your Transfer is now under review. You will be notfied shortly after reviewing by authority.');
				return response()->json($data, $this->successStatus);
			}
		}else{
			return response()->json(['result' => false, 'message' => _lang('Error Occured, Please try again !')]);		
		}
    }
	
	/** Transfer Between Users **/
	public function transfer_between_users(Request $request)
    {	
        	
		$validator = Validator::make($request->all(), [
			'amount' 		 => 'required|numeric',
			'debit_account'  => 'required',
			//'user_id'        => 'required',
			'credit_account' => 'required|different:debit_account',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);			
		}
		
		$account = Account::find($request->debit_account);

		//Generate Fee 
		$fee = generate_fee( $request->amount, $account->account_type->tbu_fee, $account->account_type->tbu_fee_type );
			

		//Check available balance
		if(get_account_balance($request->debit_account) < ($request->amount + $fee)){
			return response()->json(['result' => false, 'message' => _lang('Insufficient balance !')]);
		}
		
		$user_id = auth('api')->user()->id;
		
		$user_account = Account::where('account_number',$request->credit_account)
		                         ->where('user_id','!=',$user_id)
								 ->first();
		if(! $user_account){
			return response()->json(['result' => false, 'message' => _lang('Invalid User account !')]);
		}						 
		
		DB::beginTransaction();
		

		/* Status will only apply on credit account */
		$status = 'complete';
		if(get_option('tbu_approval') == 'yes'){
			$status = 'pending';
		}
		
		
		//Make Debit Transaction
		$debit = new Transaction();
		$debit->user_id = $user_id;
		$debit->amount = $request->input('amount');
		$debit->account_id = $request->input('debit_account');
		$debit->dr_cr = 'dr';
		$debit->type = 'transfer';
		$debit->status = $status;
		$debit->note = $request->input('note');
		$debit->created_by = $user_id;
		$debit->updated_by = $user_id;
		$debit->save();


		//Make fee Transaction
		if($fee > 0) {
			$fee_debit = new Transaction();
			$fee_debit->user_id = $user_id;
			$fee_debit->amount = $fee;
			$fee_debit->account_id = $request->input('debit_account');
			$fee_debit->dr_cr = 'dr';
			$fee_debit->type = 'fee';
			$fee_debit->status = $status;
			$fee_debit->parent_id = $debit->id;
			$fee_debit->note = _lang('Transfer Between User Fee');
			$fee_debit->created_by = $user_id;
			$fee_debit->updated_by = $user_id;
			$fee_debit->save();
		}

		
		//Make Credit Transaction
		$credit = new Transaction();
		$credit->user_id = $user_account->user_id;
		$credit->account_id = $user_account->id;
		$credit->amount = convert_currency(account_currency( $debit->account_id ), account_currency($credit->account_id), $request->amount);
		$credit->dr_cr = 'cr';
		$credit->type = 'transfer';
		$credit->status = $status;
		$credit->parent_id = $debit->id;
		$credit->note = $request->input('note');
		$credit->created_by = $user_id;
		$credit->updated_by = $user_id;
		$credit->save();

		DB::commit();
		
		if($credit->id > 0){
			$data['result'] =  true;
			$data['data']= $credit;
			
			if($status == 'complete'){
				$data['message'] =  _lang('Money Transfer Sucessfully');
				return response()->json($data, $this->successStatus);
			}else{
				$data['message'] =  _lang('Your Transfer is now under review. You will be notfied shortly after reviewing by authority.');
				return response()->json($data, $this->successStatus);
			}
		}else{
			return response()->json(['result' => false, 'message' => _lang('Error Occured, Please try again !')]);		
		}
    }
	
	/** Card Funding Transfer **/
    public function card_funding_transfer(Request $request){
	
		$validator = Validator::make($request->all(), [
			'amount' 		=> 'required|numeric',
			'debit_account' => 'required',
			'card' 			=> 'required',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);			
		}
		
		$account = Account::find($request->debit_account);

		//Generate Fee 
		$fee = generate_fee( $request->amount, $account->account_type->cft_fee, $account->account_type->cft_fee_type );
		
		
		//Check available balance
		if(get_account_balance($request->debit_account) < ($request->amount + $fee)){
			return response()->json(['result' => false, 'message' => _lang('Insufficient balance !')]);		
		}
		
		DB::beginTransaction();
		
		$user_id = auth('api')->user()->id;

		/* Status will only apply on credit account */
		$status = 'pending';
		
		//Make Debit Transaction
		$debit = new Transaction();
		$debit->user_id = $user_id;
		$debit->amount = $request->input('amount');
		$debit->account_id = $request->input('debit_account');
		$debit->dr_cr = 'dr';
		$debit->type = 'card_transfer';
		$debit->status = 'pending';
		$debit->note = $request->input('note');
		$debit->created_by = $user_id;
		$debit->updated_by = $user_id;
		$debit->save();
		

		//Create Wire Transfer Details
		$cardtransaction = new CardTransaction();
		$cardtransaction->card_id = $request->input('card');
		$cardtransaction->dr_cr = 'cr';
		$cardtransaction->amount = convert_currency(account_currency($debit->account_id), card_currency($cardtransaction->card_id), $debit->amount);
		$cardtransaction->note = $request->input('note');
		$cardtransaction->status = 0;
		$cardtransaction->transaction_id = $debit->id;
		$cardtransaction->created_by = $user_id;
		$cardtransaction->updated_by = $user_id;
	
		$cardtransaction->save();


		//Make fee Transaction
		if($fee > 0) {
			$fee_debit = new Transaction();
			$fee_debit->user_id = $user_id;
			$fee_debit->amount = $fee;
			$fee_debit->account_id = $request->input('debit_account');
			$fee_debit->dr_cr = 'dr';
			$fee_debit->type = 'fee';
			$fee_debit->status = 'pending';
			$fee_debit->parent_id = $debit->id;
			$fee_debit->note = _lang('Card Funding Transfer Fee');
			$fee_debit->created_by = $user_id;
			$fee_debit->updated_by = $user_id;
			$fee_debit->save();
		}
		
		DB::commit();
		
		if($cardtransaction->transaction_id > 0){
			$data['result'] =  true;
			$data['data']= $cardtransaction;
			
			if($status == 'complete'){
				$data['message'] =  _lang('Money Transfer Sucessfully');
				return response()->json($data, $this->successStatus);
			}else{
				$data['message'] =  _lang('Your Card Funding Transfer is processing. You will be notfied within 2-3 business days after reviewing by authority. Your Money will be returned back to your debit account if authority reject your transfer.');
				return response()->json($data, $this->successStatus);
			}
		}else{
			return response()->json(['result' => false, 'message' => _lang('Error Occured, Please try again !')]);		
		}

    }
	
	
	/** Outgoing Wire Transfer **/
    public function outgoing_wire_transfer(Request $request)
    {		
		$validator = Validator::make($request->all(), [
			'amount' 			=> 'required|numeric',
			'debit_account' 	=> 'required',
			'currency' 			=> 'required',
			'swift' 			=> 'required|max:50',
			'bank_name' 		=> 'required',
			'bank_country' 		=> 'required',
			'customer_name'		=> 'required',
			'customer_iban' 	=> 'required|max:50',
			'reference_message' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);			
		}
		
		$account = Account::find($request->debit_account);

		//Generate Fee 
		$fee = generate_fee( $request->amount, $account->account_type->owt_fee, $account->account_type->owt_fee_type );	
		
		//Check available balance
		if(get_account_balance($request->debit_account) < ($request->amount + $fee)){
			return response()->json(['result' => false, 'message' => _lang('Insufficient balance !')]);		
		}
		
		DB::beginTransaction();
		
		$user_id = auth('api')->user()->id;

		/* Status will only apply on credit account */
		$status = 'pending';
		
		//Make Debit Transaction
		$debit = new Transaction();
		$debit->user_id = $user_id;
		$debit->amount = $request->input('amount');
		$debit->account_id = $request->input('debit_account');
		$debit->dr_cr = 'dr';
		$debit->type = 'wire_transfer';
		$debit->status = 'pending';
		$debit->note = $request->input('note');
		$debit->created_by = $user_id;
		$debit->updated_by = $user_id;
		$debit->save();
		

		//Create Wire Transfer Details
		$wiretransfer = new WireTransfer();
		$wiretransfer->transaction_id = $debit->id;
		$wiretransfer->swift = $request->input('swift');
		$wiretransfer->bank_name = $request->input('bank_name');
		$wiretransfer->bank_address = $request->input('bank_address');
		$wiretransfer->bank_country = $request->input('bank_country');
		$wiretransfer->rtn = $request->input('rtn');
		$wiretransfer->customer_name = $request->input('customer_name');
		$wiretransfer->customer_address = $request->input('customer_address');
		$wiretransfer->customer_iban = $request->input('customer_iban');
		$wiretransfer->reference_message = $request->input('reference_message');
		$wiretransfer->currency = $request->input('currency');
		$wiretransfer->amount = convert_currency(account_currency($debit->account_id), $wiretransfer->currency, $debit->amount);
	
		$wiretransfer->save();

		//Make fee Transaction
		if($fee > 0) {
			$fee_debit = new Transaction();
			$fee_debit->user_id = $user_id;
			$fee_debit->amount = $fee;
			$fee_debit->account_id = $request->input('debit_account');
			$fee_debit->dr_cr = 'dr';
			$fee_debit->type = 'fee';
			$fee_debit->status = 'pending';
			$fee_debit->parent_id = $debit->id;
			$fee_debit->note = _lang('Outgoing Wire Transfer Fee');
			$fee_debit->created_by = $user_id;
			$fee_debit->updated_by = $user_id;
			$fee_debit->save();
		}
		
		DB::commit();
		
		if($wiretransfer->transaction_id > 0){
			
			$data['result'] =  true;
			$data['data']= $wiretransfer;
			
			if($status == 'complete'){
				$data['message'] =  _lang('Money Transfer Sucessfully');
				return response()->json($data, $this->successStatus);
			}else{
				$data['message'] =  _lang('Your Outgoing Wire Transfer is processing. You will be notfied within 2-3 business days after reviewing by authority. Your Money will be returned back to your debit account if authority reject your transfer.');
				return response()->json($data, $this->successStatus);
			}
		}else{
			return response()->json(['result' => false, 'message' => _lang('Error Occured, Please try again !')]);		
		}
    }

}
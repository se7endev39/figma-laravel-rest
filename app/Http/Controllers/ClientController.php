<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Document;
use App\Transaction;
use App\CardTransaction;
use App\Account;
use App\Card;
use App\Deposit;
use App\Withdraw;
use App\WireTransfer;
use App\Loan;
use App\User;
use App\LoanPayment;
use App\Utilities\LoanCalculator as Calculator;
use Validator;
use Auth;
use DB;

class ClientController extends Controller
{

	public function __construct(){
 		date_default_timezone_set(get_option('timezone'));
	} 
	 
    public function submit_documents(Request $request)
    {
		if (! $request->isMethod('post')){
			return view('backend.user_panel.submit_documents');
		}else{
			
			$validator = Validator::make($request->all(), [
				'nid_passport' => 'required|mimes:jpeg,png,jpg,pdf',
				'electric_bill' => 'required|mimes:jpeg,png,jpg,pdf',
			]);
			
			if ($validator->fails()) {
				if($request->ajax()){ 
					return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
				}else{
					return back()->withErrors($validator)
								 ->withInput();
				}			
			}
			
			// Upload NID / Passport
			if($request->hasfile('nid_passport')){
				$file = $request->file('nid_passport');
				$nid_passport = 'Identification_Document_'.time().'.'.$file->getClientOriginalExtension();
				$file->move(public_path()."/uploads/documents/", $nid_passport);
				
			    $document = new Document();
				$document->document_name = "Identification Document";
				$document->document = $nid_passport;
				$document->user_id = Auth::user()->id;
			
				$document->save();
			}
			
			// Upload Electric Bill
			if($request->hasfile('electric_bill')){
				$file = $request->file('electric_bill');
				$electric_bill = 'Address_Verification_'.time().'.'.$file->getClientOriginalExtension();
				$file->move(public_path()."/uploads/documents/", $electric_bill);
				
			    $document = new Document();
				$document->document_name = "Address Verification Document";
				$document->document = $electric_bill;
				$document->user_id = Auth::user()->id;
			
				$document->save();
			}
			
			//Update User table
			$user = Auth::user();
			$user->document_submitted_at = Carbon::now();
			$user->save();
			
			
			return back()->with('document_success', _lang('Thank you for submitting your document. You will be notified soon after reviewing your documents by authority.'));
		
		}
	
	}

	/* Profile Overview */
	public function overview(){
		$user = Auth::user();
		return view('backend.user_panel.profile_overview', compact('user'));
	}

    /*	View Account details */
	public function view_account_details( Request $request, $id ){
 		//$account = Account::where('id',$id)->where('user_id',Auth::id())->first();
 		$account = Account::select('accounts.*',DB::raw("((SELECT IFNULL(SUM(amount),0) 
                           FROM transactions WHERE dr_cr = 'cr' AND status = 'complete' AND account_id = accounts.id) - 
                           (SELECT IFNULL(SUM(amount),0) FROM transactions WHERE dr_cr = 'dr' 
                           AND status ='complete' AND account_id = accounts.id)) as balance"))
 		                   ->where('id', $id)
 		                   ->where('user_id', Auth::id())
                           ->orderBy('id','desc')
                           ->first();
		if( $request->ajax() ){
		    return view('backend.user_panel.modal.view_account', compact('account','id'));
		}
	}

	/*	View Transaction details */
	public function view_transaction( Request $request, $id ){
		$transaction = Transaction::where('id',$id)->where('user_id',Auth::id())->first();
		if( $request->ajax() ){
			return view('backend.user_panel.modal.view_transaction', compact('transaction','id'));
		} 
	}


    /* My Loans */
	public function my_loans(){
		$loans = Loan::select('loans.*')
					 ->where('borrower_id', Auth::id())
					 ->orderBy("loans.id","desc")
					 ->get();

		return view('backend.user_panel.loan.my_loans', compact('loans'));
	}

	public function view_loan_details($loan_id){
		$data = array();
		$loan = Loan::where('id',$loan_id)
							->where('borrower_id', Auth::id())
							->first();

		if($loan){
			return view('backend.user_panel.loan.loan_details', compact('loan'));
		}
	}

	/** Apply New Loan **/
	public function apply_loan(Request $request){
		if(request()->isMethod('get')){

			return view('backend.user_panel.loan.apply_loan');

		}else if(request()->isMethod('post')){

			@ini_set('max_execution_time', 0);
	        @set_time_limit(0);

	        $validator = Validator::make($request->all(), [
	            'loan_product_id' => 'required',
				'account_id' => 'required',
	            'first_payment_date' => 'required',
				'applied_amount' => 'required|numeric',
				'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
	        ]);

	        if ($validator->fails()) {
	            if($request->ajax()){ 
	                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
	            }else{
	                return redirect()->route('loans.create')
	                	             ->withErrors($validator)
	                	             ->withInput();
	            }			
	        }
		
	        $attachment = "";
	        if($request->hasfile('attachment'))
	        {
	            $file = $request->file('attachment');
	            $attachment = time().$file->getClientOriginalName();
	            $file->move(public_path()."/uploads/media/", $attachment);
	        }

	        DB::beginTransaction();

	        $loan = new Loan();
	        $loan->loan_product_id = $request->input('loan_product_id');
			$loan->borrower_id = Auth::id();
			$loan->account_id = $request->input('account_id');
	        $loan->first_payment_date = $request->input('first_payment_date');
			//$loan->release_date = $request->input('release_date');
			$loan->applied_amount = $request->input('applied_amount');
			$loan->late_payment_penalties = 0;
			$loan->attachment = $attachment;
			$loan->description = $request->input('description');
			$loan->remarks = $request->input('remarks');
			$loan->created_user_id = Auth::id();

	        $loan->save();

	        // Create Loan Repayments 
	        $calculator = new Calculator(
	                        $loan->applied_amount, 
	                        $loan->first_payment_date, 
	                        $loan->loan_product->interest_rate, 
	                        $loan->loan_product->term, 
	                        $loan->loan_product->term_period, 
	                        $loan->late_payment_penalties
	                    );

	        if($loan->loan_product->interest_type == 'flat_rate'){
	            $repayments = $calculator->get_flat_rate();
	        }else if($loan->loan_product->interest_type == 'fixed_rate'){
	            $repayments = $calculator->get_fixed_rate();
	        }else if($loan->loan_product->interest_type == 'mortgage'){
	            $repayments = $calculator->get_mortgage();
	        }else if($loan->loan_product->interest_type == 'one_time'){
	            $repayments = $calculator->get_one_time();
	        }

	        $loan->total_payable = $calculator->payable_amount;
	        $loan->save();

	        foreach($repayments as $repayment){
	            $loan_repayment = new \App\LoanRepayment();
	            $loan_repayment->loan_id = $loan->id;
	            $loan_repayment->repayment_date = $repayment['date'];
	            $loan_repayment->amount_to_pay = $repayment['amount_to_pay'];
	            $loan_repayment->penalty = $repayment['penalty'];
	            $loan_repayment->principal_amount = $repayment['principle_amount'];
	            $loan_repayment->interest = $repayment['interest'];
	            $loan_repayment->balance = $repayment['balance'];
	            $loan_repayment->save();
	        }

	        DB::commit();

	        if($loan->id > 0){
	        	 return redirect('user/my_loans')->with('success', _lang('Your Loan application subbmited sucessfully and your application is under review'));
	        }
		}
	}

	public function loan_payment(Request $request, $loan_id){
		if(request()->isMethod('get')){
			$loan = Loan::find($loan_id);
			return view('backend.user_panel.loan.payment', compact('loan'));
		}else if(request()->isMethod('post')){

			$validator = Validator::make($request->all(), [
				'account_id'     => 'required',
	        ]);

	        if ($validator->fails()) {
	            if($request->ajax()){ 
	                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
	            }else{
	                return redirect()->route('loan_payments.create')
	                	             ->withErrors($validator)
	                	             ->withInput();
	            }			
	        }


	        DB::beginTransaction();
	        
	        $loan = Loan::find($loan_id);
	        $repayment = $loan->next_payment;

	        //Create Transaction
	        $penalty = date('Y-m-d') > $repayment->repayment_date ? $repayment->penalty : 0;
	        $base_amount = $repayment->amount_to_pay + $penalty;
	        $amount = convert_currency(account_currency($loan->account_id), account_currency($request->account_id), $base_amount);

	        //Check Available Balance
	        if(get_account_balance($request->account_id) < $amount){
	            return back()->with('error', _lang('Insufficient balance !'));
	        }

	        $transaction = new Transaction();
	        $transaction->user_id = $loan->borrower_id;
	        $transaction->amount = $amount;
	        $transaction->account_id = $request->account_id; //Debit Account
	        $transaction->dr_cr = 'dr';
	        $transaction->type = 'loan_payment';
	        $transaction->status = 'complete';
	        $transaction->note = 'Loan Repayment';
	        $transaction->loan_id = $loan->id;
	        $transaction->created_by = Auth::id();
	        $transaction->updated_by = Auth::id();
	    
	        $transaction->save();
		
	        
	        $loanpayment = new LoanPayment();
	        $loanpayment->loan_id = $loan->id;
			$loanpayment->paid_at = date('Y-m-d');
	        $loanpayment->late_penalties = $penalty; //it's optionals
			$loanpayment->interest = $repayment->interest;
			$loanpayment->amount_to_pay = $repayment->amount_to_pay;
			$loanpayment->remarks = $request->remarks;
	        $loanpayment->transaction_id = $transaction->id;
			$loanpayment->repayment_id = $repayment->id;
			$loanpayment->user_id = Auth::id();

	        $loanpayment->save();

	        //Update Loan Balance
	        $repayment->status = 1;
	        $repayment->save();

	        
	        $loan->total_paid = $loan->total_paid + $repayment->amount_to_pay;
	        if($loan->total_paid >= $loan->applied_amount){
	            $loan->status = 2;
	        }
	        $loan->save();

	        DB::commit();

	        if(! $request->ajax()){
	           return redirect('dashboard')->with('success', _lang('Payment Made Sucessfully'));
	        }else{
	           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'), 'data'=>$loanpayment, 'table' => '#loan_payments_table']);
	        }
		}
	}

	/** Transfer Between Accounts **/
    public function transfer_between_accounts(Request $request)
    {
        @ini_set('max_execution_time', 0);
		@set_time_limit(0);

        if (! $request->isMethod('post')){
			return view('backend.user_panel.transfer.transfer_between_accounts');
		}else{	
		    $validator = Validator::make($request->all(), [
				'amount' => 'required|numeric',
				'debit_account' => 'required',
				'credit_account' => 'required|different:debit_account',
			]);
			
			if ($validator->fails()) {
				if($request->ajax()){ 
					return response()->json(['result'=>'error','message' => $validator->errors()->all()]);
				}else{
					return back()->withErrors($validator)
								 ->withInput();
				}			
			}
			
			$account = Account::find($request->debit_account);
			$account2 = Account::find($request->credit_account);

			//Generate Fee 
			$fee = generate_fee( $request->amount, $account->account_type->tba_fee, $account->account_type->tba_fee_type );
			
			$currency_convert_fee = 0;
			if($account->account_type->currency_id != $account2->account_type->currency_id){
				$currency_exchange_fee = (float)get_option('currency_exchange_fee',0);
				$currency_convert_fee = ($currency_exchange_fee / 100) * $request->amount;
			}
			
			
			//Check available balance
			if(get_account_balance($request->debit_account) < ($request->amount + $fee + $currency_convert_fee)){
				return back()->with('error', _lang('Insufficient balance !'));
			}
			
			DB::beginTransaction();
            

            /* Status will only apply on credit account */
			$status = 'complete';
			if(get_option('tba_approval') == 'yes'){
				$status = 'pending';
			}
			
			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = Auth::id();
			$debit->amount = $request->input('amount');
			$debit->account_id = $request->input('debit_account');
			$debit->dr_cr = 'dr';
			$debit->type = 'transfer';
			$debit->status = $status;
			$debit->note = $request->input('note');
			$debit->created_by = Auth::id();
			$debit->updated_by = Auth::id();
			$debit->save();

			//Make fee Transaction
			if($fee > 0) {
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = $status;
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Transfer Between Account Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			if($currency_convert_fee > 0){
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $currency_convert_fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = $status;
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Currency Exchange Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			//Make Credit Transaction
			$credit = new Transaction();
			$credit->user_id = Auth::id();
			$credit->account_id = $request->input('credit_account');
			$credit->amount = convert_currency(account_currency( $debit->account_id ), account_currency($credit->account_id), $request->amount);
			$credit->dr_cr = 'cr';
			$credit->type = 'transfer';
			$credit->status = $status;
			$credit->parent_id = $debit->id;
			$credit->note = $request->input('note');
			$credit->created_by = Auth::id();
			$credit->updated_by = Auth::id();
			$credit->save();
	
			
			DB::commit();
			
			if($credit->id > 0){
				if($status == 'complete'){
					
					//Registering Event
					event(new \App\Events\DepositMoney($credit));
					
					return back()->with('success', _lang('Money Transfer Sucessfully'));
				}else{
					return back()->with('success', _lang('Your Transfer is now under review. You will be notfied shortly after reviewing by authority.'));
				}
		    }else{
		    	return back()->with('error', _lang('Error Occured, Please try again !'));
		    }
		}
    }


    public function transfer_between_users(Request $request)
    {	
    	@ini_set('max_execution_time', 0);
		@set_time_limit(0);

        if (! $request->isMethod('post')){
			return view('backend.user_panel.transfer.transfer_between_users');
		}else{	
		    $validator = Validator::make($request->all(), [
				'amount' => 'required|numeric',
				'debit_account' => 'required',
				'user_email' => 'required',
				'credit_account' => 'required|different:debit_account',
			]);
			
			if ($validator->fails()) {
				if($request->ajax()){ 
					return response()->json(['result'=>'error','message' => $validator->errors()->all()]);
				}else{
					return back()->withErrors($validator)
								 ->withInput();
				}			
			}
			
			$user = User::where('email',$request->user_email)->first();
			if(! $user){
				return back()->with('error', _lang('User account not found !'))->withInput();
			}
			
			$user_account = Account::where('user_id', $user->id)
								   ->where('account_number', $request->credit_account)
								   ->first();
			if(! $user_account){
				return back()->with('error', _lang('Account number not found !'))->withInput();
			}
			
			$account = Account::find($request->debit_account);
			$account2 = Account::find($request->credit_account);

			//Generate Fee 
			$fee = generate_fee( $request->amount, $account->account_type->tbu_fee, $account->account_type->tbu_fee_type );
			
			$currency_convert_fee = 0;
			if($account->account_type->currency_id != $account2->account_type->currency_id){
				$currency_exchange_fee = (float)get_option('currency_exchange_fee',0);
				$currency_convert_fee = ($currency_exchange_fee / 100) * $request->amount;
			}
			

			//Check available balance
			if(get_account_balance($request->debit_account) < ($request->amount + $fee + $currency_convert_fee )){
				return back()->with('error', _lang('Insufficient balance !'));
			}
			
			DB::beginTransaction();
            

            /* Status will only apply on credit account */
			$status = 'complete';
			if(get_option('tbu_approval') == 'yes'){
				$status = 'pending';
			}
			
			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = Auth::id();
			$debit->amount = $request->input('amount');
			$debit->account_id = $request->input('debit_account');
			$debit->dr_cr = 'dr';
			$debit->type = 'transfer';
			$debit->status = $status;
			$debit->note = $request->input('note');
			$debit->created_by = Auth::id();
			$debit->updated_by = Auth::id();
			$debit->save();


			//Make fee Transaction
			if($fee > 0) {
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = $status;
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Transfer Between User Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			if( $currency_convert_fee > 0 ){
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $currency_convert_fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = $status;
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Currency Exchange Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}

			
			//Make Credit Transaction
			$credit = new Transaction();
			$credit->user_id = $user_account->id;
			$credit->account_id = $user_account->id;
			$credit->amount = convert_currency(account_currency( $debit->account_id ), account_currency($credit->account_id), $request->amount);
			$credit->dr_cr = 'cr';
			$credit->type = 'transfer';
			$credit->status = $status;
			$credit->parent_id = $debit->id;
			$credit->note = $request->input('note');
			$credit->created_by = Auth::id();
			$credit->updated_by = Auth::id();
			$credit->save();

			DB::commit();
			
			if($credit->id > 0){
				if($status == 'complete'){
					
					//Registering Event
					event(new \App\Events\DepositMoney($credit));
					
					return back()->with('success', _lang('Money Transfer Sucessfully'));
				}else{
					return back()->with('success', _lang('Your Transfer is now under review. You will be notfied shortly after reviewing by authority.'));
				}
		    }else{
		    	return back()->with('error', _lang('Error Occured, Please try again !'));
		    }
		}
    }

    /** Card Funding Transfer **/
    public function card_funding_transfer(Request $request){
    	@ini_set('max_execution_time', 0);
		@set_time_limit(0);

		if (! $request->isMethod('post')){
			return view('backend.user_panel.transfer.card_funding_transfer');
		}else{	
		    $validator = Validator::make($request->all(), [
				'amount' => 'required|numeric',
				'debit_account' => 'required',
				'card' => 'required',
			]);
			
			if ($validator->fails()) {
				if($request->ajax()){ 
					return response()->json(['result'=>'error','message' => $validator->errors()->all()]);
				}else{
					return back()->withErrors($validator)
								 ->withInput();
				}			
			}
			
			$account = Account::find($request->debit_account);
			$card = Card::find($request->card);
			
			//Generate Fee 
			$fee = generate_fee( $request->amount, $account->account_type->cft_fee, $account->account_type->cft_fee_type );
			
			$currency_convert_fee = 0;
			if($account->account_type->currency_id != $card->card_type->currency_id){
				$currency_exchange_fee = (float)get_option('currency_exchange_fee',0);
				$currency_convert_fee = ($currency_exchange_fee / 100) * $request->amount;
			}
			
			//Check available balance
			if(get_account_balance($request->debit_account) < ($request->amount + $fee + $currency_convert_fee)){
				return back()->with('error', _lang('Insufficient balance !'));
			}
			
			DB::beginTransaction();
            

            /* Status will only apply on credit account */
			$status = 'pending';
			
			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = Auth::id();
			$debit->amount = $request->input('amount');
			$debit->account_id = $request->input('debit_account');
			$debit->dr_cr = 'dr';
			$debit->type = 'card_transfer';
			$debit->status = 'pending';
			$debit->note = $request->input('note');
			$debit->created_by = Auth::id();
			$debit->updated_by = Auth::id();
			$debit->save();
			

            //Create Card Transfer Details
		    $cardtransaction = new CardTransaction();
		    $cardtransaction->card_id = $request->input('card');
			$cardtransaction->dr_cr = 'cr';
			$cardtransaction->amount = convert_currency(account_currency($debit->account_id), card_currency($cardtransaction->card_id), $debit->amount);
			$cardtransaction->note = $request->input('note');
			$cardtransaction->status = 0;
			$cardtransaction->transaction_id = $debit->id;
			$cardtransaction->created_by = Auth::id();
			$cardtransaction->updated_by = Auth::id();
		
	        $cardtransaction->save();


	        //Make fee Transaction
			if($fee > 0) {
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = 'pending';
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Card Funding Transfer Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			if( $currency_convert_fee > 0 ){
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $currency_convert_fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = $status;
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Currency Exchange Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			
			DB::commit();
			
			if($cardtransaction->transaction_id > 0){
				if($status == 'complete'){
					return back()->with('success', _lang('Money Transfer Sucessfully'));
				}else{
					return back()->with('success', _lang('Your Card Funding Transfer is processing. You will be notfied within 2-3 business days after reviewing by authority. Your Money will be returned back to your debit account if authority reject your transfer.'));
				}
		    }else{
		    	return back()->with('error', _lang('Error Occured, Please try again !'));
		    }
		}

    }


    /** Outgoing Wire Transfer **/
    public function outgoing_wire_transfer(Request $request)
    {	

    	@ini_set('max_execution_time', 0);
		@set_time_limit(0);
		
        if (! $request->isMethod('post')){
			return view('backend.user_panel.transfer.outgoing_wire_transfer');
		}else{	
		    $validator = Validator::make($request->all(), [
				'amount' => 'required|numeric',
				'debit_account' => 'required',
				'currency' => 'required',
				'swift' => 'required|max:50',
				'bank_name' => 'required',
				'bank_country' => 'required',
				'customer_name' => 'required',
				'customer_iban' => 'required|max:50',
				'reference_message' => 'required'
			]);
			
			if ($validator->fails()) {
				if($request->ajax()){ 
					return response()->json(['result'=>'error','message' => $validator->errors()->all()]);
				}else{
					return back()->withErrors($validator)
								 ->withInput();
				}			
			}
			
			$account = Account::find($request->debit_account);

			//Generate Fee 
			$fee = generate_fee( $request->amount, $account->account_type->owt_fee, $account->account_type->owt_fee_type );
			
			$currency_convert_fee = 0;
			if($account->account_type->currency->name != $request->currency){
				$currency_exchange_fee = (float)get_option('currency_exchange_fee',0);
				$currency_convert_fee = ($currency_exchange_fee / 100) * $request->amount;
			}
			
			//Check available balance
			if(get_account_balance($request->debit_account) < ($request->amount + $fee + $currency_convert_fee)){
				return back()->with('error', _lang('Insufficient balance !'));
			}
			
			DB::beginTransaction();
            

            /* Status will only apply on credit account */
			$status = 'pending';
			
			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = Auth::id();
			$debit->amount = $request->input('amount');
			$debit->account_id = $request->input('debit_account');
			$debit->dr_cr = 'dr';
			$debit->type = 'wire_transfer';
			$debit->status = 'pending';
			$debit->note = $request->input('note');
			$debit->created_by = Auth::id();
			$debit->updated_by = Auth::id();
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
			if( $fee > 0 ) {
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = 'pending';
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Outgoing Wire Transfer Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			if( $currency_convert_fee > 0 ){
				$fee_debit = new Transaction();
				$fee_debit->user_id = Auth::id();
				$fee_debit->amount = $currency_convert_fee;
				$fee_debit->account_id = $request->input('debit_account');
				$fee_debit->dr_cr = 'dr';
				$fee_debit->type = 'fee';
				$fee_debit->status = $status;
				$fee_debit->parent_id = $debit->id;
				$fee_debit->note = _lang('Currency Exchange Fee');
				$fee_debit->created_by = Auth::id();
				$fee_debit->updated_by = Auth::id();
				$fee_debit->save();
			}
			
			DB::commit();
			
			if($wiretransfer->transaction_id > 0){
				if($status == 'complete'){
					return back()->with('wire_success', _lang('Money Transfer Sucessfully'));
				}else{
					return back()->with('wire_success', _lang('Your Outgoing Wire Transfer is processing. You will be notfied within 2-3 business days after reviewing by authority. Your Money will be returned back to your debit account if authority reject your transfer.'));
				}
		    }else{
		    	return back()->with('error', _lang('Error Occured, Please try again !'));
		    }
		}
    }

    /* Referral Commissions */
    public function referral_commissions(){
        $data = array();
        $data['referral_commissions'] = \App\ReferralCommission::where('user_id',Auth::id())
                                                               ->where('status',1)
                                                               ->selectRaw("currency_id, sum(amount) as amount")
                                                               ->groupBy('currency_id')
                                                               ->get();

        return view('backend.user_panel.referral_commissions', $data);                                                      
    }

 	/* Transfer Referral Commissions to account */
    public function transfer_referral_commissions(Request $request){
        $currency_id = $request->currency_id;

        DB::beginTransaction();
        $commission = \App\ReferralCommission::where('user_id',Auth::id())
	                                         ->where('status',1)
	                                         ->where('currency_id', $currency_id)
	                                         ->selectRaw("currency_id, sum(amount) as amount")
	                                         ->groupBy('currency_id')
	                                         ->first();

        $credit = new Transaction();
		$credit->user_id = Auth::id();
		$credit->account_id = $request->account_id;
		$credit->amount = convert_currency($commission->currency->name, account_currency($credit->account_id), $commission->amount);
		$credit->dr_cr = 'cr';
		$credit->type = 'revenue';
		$credit->status = 'complete';
		$credit->note = _lang('Referral Commission');
		$credit->created_by = Auth::id();
		$credit->updated_by = Auth::id();
		$credit->save();

		\App\ReferralCommission::where('user_id',Auth::id())
                               ->where('status',1)
                               ->where('currency_id', $currency_id)
                               ->update(['status' => 0]);

		DB::commit();

		if($credit->id > 0){
			return back()->with('success', _lang('Money added to your account Sucessfully.'));
	    }else{
	    	return back()->with('error', _lang('Error Occured, Please try again !'));
	    }

    }
	
	/* Show Merchant API Page */
    public function merchant_api(){
		if(Auth::user()->account_type != 'business'){
			abort(403);
		}
        return view('backend.user_panel.merchant_api');                                                      
    }

	

	
}
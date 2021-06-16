<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Transaction;
use App\Deposit;
use App\Withdraw;
use App\Account;
use App\Currency;
use App\FinanceTransaction;
use DB;

class ReportController extends Controller
{
	
	public function __construct()
    {
		date_default_timezone_set( get_option('timezone', get_option('timezone','Asia/Dhaka')) );	
	}


	public function account_statement(Request $request, $view=""){
		
		if( $view == '' ){
		    return view('backend.reports.account_statement');	
		} else if ( $view == 'view' ){
		   $data = array();
		   $date1 = $request->date1;
		   $date2 = $request->date2;
		   $status = $request->status;
		   $account = $request->account;

		   
		   if( $status == "all" ){			
		   		$data['report_data'] = Transaction::where('account_id', $account)
		   		                                  //->where('type', 'transfer')
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }else{
			   $data['report_data'] = Transaction::where('account_id', $account)
		   		                                  //->where('type', 'transfer')
		   		                                  ->where('status', $status)
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }
		   
		   $data['status'] = $request->status;
		   $data['date1'] = $request->date1;
		   $data['date2'] = $request->date2;
		   $data['account'] = $request->account;
		   $data['acc'] = Account::find($account);
		   
		   return view('backend.reports.account_statement',$data);	
		 }
    }

	 public function transactions_report(Request $request, $view=""){
		
		if( $view == '' ){
		   return view('backend.reports.transfer_report');	
		} else if ( $view == 'view' ){
		   $data = array();
		   $date1 = $request->date1;
		   $date2 = $request->date2;
		   $type = $request->type;
		   $status = $request->status;

		   
		   if( $status == "all" ){			
		   		$data['report_data'] = Transaction::when($type, function ($query, $type) {
										   		  	 if($type != 'all'){
									                     return $query->where('type', $type);
									                 }
								                  })
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }else{
			   $data['report_data'] = Transaction::when($type, function ($query, $type) {
										   		  	 if($type != 'all'){
									                     return $query->where('type', $type);
									                 }
								                  })
		   		                                  ->where('status', $status)
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }
		   
		   $data['type'] = $request->type;
		   $data['status'] = $request->status;
		   $data['date1'] = $request->date1;
		   $data['date2'] = $request->date2;
		   
		   return view('backend.reports.transfer_report',$data);	
		 }
    }

	public function profit_and_loss_report(Request $request, $view=""){
		
		if( $view == '' ){

		    return view('backend.reports.profit_and_loss_report');	

		} else if ( $view == 'view' ){
		    $data = array();
		    $date1 = $request->date1;
		    $date2 = $request->date2;
			
            $currency_list = array();
			foreach(Currency::all() as $currency){
				$currency_list[$currency->name] = $currency;
			}
			$base_rate = $currency_list[get_base_currency()]['exchange_rate'];
		    
										  
			$transaction_fees = DB::table('transactions')
								->join('accounts', 'accounts.id', 'transactions.account_id')
								->join('account_types','accounts.account_type_id','account_types.id')
								->join('currency','account_types.currency_id','currency.id')
								->selectRaw('currency.name as currency, sum(amount) as profit')
								->where('transactions.type','fee')
								->where('transactions.status','complete')
								->whereNull('transactions.custom_fee_id')
								->whereBetween('transactions.created_at', [$date1, $date2])
								->groupBy('currency.name')
								->get();
								
			$custom_fees = DB::table('transactions')
								->join('accounts', 'accounts.id', 'transactions.account_id')
								->join('account_types','accounts.account_type_id','account_types.id')
								->join('currency','account_types.currency_id','currency.id')
								->selectRaw('currency.name as currency, sum(amount) as profit')
								->where('transactions.type','fee')
								->where('transactions.status','complete')
								->whereNotNull('transactions.custom_fee_id')
								->whereBetween('transactions.created_at', [$date1, $date2])
								->groupBy('currency.name')
								->get();

			$loan_profit = DB::table('loans')
								->join('loan_payments', 'loans.id', 'loan_payments.loan_id')
								->join('accounts', 'accounts.id', 'loans.account_id')
								->join('account_types','accounts.account_type_id','account_types.id')
								->join('currency','account_types.currency_id','currency.id')
								->selectRaw('currency.name as currency, sum(late_penalties + interest) as profit')
								->whereBetween('paid_at', [$date1, $date2])
								->groupBy('currency.name')
								->get();					
								
								
			$data['transaction_fees_amount']  = 0; 					
			foreach($transaction_fees as $tf){
				$data['transaction_fees_amount']  += convert_currency_2($currency_list[$tf->currency]['exchange_rate'], $base_rate, $tf->profit);
			}	
	
			$data['custom_fees_amount'] = 0; 					
			foreach($custom_fees as $cf){
				$data['custom_fees_amount'] += convert_currency_2($currency_list[$cf->currency]['exchange_rate'], $base_rate, $cf->profit);
			}	

			$data['loan_profit_amount'] = 0; 					
			foreach($loan_profit as $lp){
				$data['loan_profit_amount'] += convert_currency_2($currency_list[$lp->currency]['exchange_rate'], $base_rate, $lp->profit);
			}

	        $data['other_incomes'] = FinanceTransaction::where('type','income')
													   ->selectRaw('sum(amount) as amount, chart_of_account_id')
													   ->whereBetween('created_at', [$date1, $date2])
													   ->groupBy('chart_of_account_id')
													   ->get();
													   
			$data['other_expenses'] = FinanceTransaction::where('type','expense')
													    ->selectRaw('sum(amount) as amount, chart_of_account_id')
													    ->whereBetween('created_at', [$date1, $date2])
													    ->groupBy('chart_of_account_id')
														->get();
														
			$referral_fees = DB::table('transactions')
								->join('accounts', 'accounts.id', 'transactions.account_id')
								->join('account_types','accounts.account_type_id','account_types.id')
								->join('currency','account_types.currency_id','currency.id')
								->selectRaw('currency.name as currency, sum(amount) as expense')
								->where('transactions.type','revenue')
								->where('transactions.status','complete')
								->whereBetween('transactions.created_at', [$date1, $date2])
								->groupBy('currency.name')
								->get();
								
			$data['referral_fees_amount']  = 0; 					
			foreach($referral_fees as $rf){
				$data['referral_fees_amount']  += convert_currency_2($currency_list[$rf->currency]['exchange_rate'], $base_rate, $rf->expense);
			}											
		   
		    $data['date1'] = $request->date1;
		    $data['date2'] = $request->date2;
		   
		    return view('backend.reports.profit_and_loss_report',$data);	
		 }
    }
	
	public function profit_report_by_user(Request $request, $view=""){
		
		if( $view == '' ){

		    return view('backend.reports.profit_report_by_user');	

		} else if ( $view == 'view' ){
		   $data = array();
		   $user_id = $request->user_id;
		   $date1 = $request->date1;
		   $date2 = $request->date2;

		   
		   $fee_profit = DB::table('transactions')
	                         ->join('accounts', 'accounts.id', 'transactions.account_id')
							 ->join('account_types','accounts.account_type_id','account_types.id')
							 ->join('currency','account_types.currency_id','currency.id')
							 ->selectRaw('currency.name as currency, amount as profit')
							 ->where('transactions.type','fee')
							 ->where('transactions.status','complete')
							 ->where('transactions.user_id',$user_id)
							 ->whereBetween('transactions.created_at', [$date1, $date2]);

			$loan_profit = DB::table('loans')
							->join('loan_payments', 'loans.id', 'loan_payments.loan_id')
							->join('accounts', 'accounts.id', 'loans.account_id')
							->join('account_types','accounts.account_type_id','account_types.id')
							->join('currency','account_types.currency_id','currency.id')
							->selectRaw('currency.name as currency, (late_penalties + interest) as profit')
							->whereBetween('paid_at', [$date1, $date2])
							->where('loans.borrower_id', $user_id);			

			$final = $fee_profit->union($loan_profit);

			$querySql = $final->toSql();
 
			$all_content_query  = DB::table(DB::raw("($querySql) as a"))->mergeBindings($final);				

		    $data['report_data'] = $all_content_query->selectRaw("currency, sum(profit) as profit")
												     ->groupBy('currency')
												     ->get();	 

			
												     
		    $data['date1'] = $request->date1;
		    $data['date2'] = $request->date2;
		    $data['user_id'] = $request->user_id;
		   
		    return view('backend.reports.profit_report_by_user',$data);	
		 }
    }

    public function deposit_report(Request $request, $view=""){
		
		if( $view == '' ){

		    return view('backend.reports.deposit_report');	

		} else if ( $view == 'view' ){
		   $data = array();
		   $date1 = $request->date1;
		   $date2 = $request->date2;

		   
		
	   		$data['report_data'] = Deposit::whereBetween('created_at', [$date1, $date2])
                                          ->orderBy('id', 'desc')
                                          ->get(); 

		   
		   $data['date1'] = $request->date1;
		   $data['date2'] = $request->date2;
		   
		   return view('backend.reports.deposit_report',$data);	
		 }
    }

    public function withdraw_report(Request $request, $view=""){
		
		if( $view == '' ){

		    return view('backend.reports.withdraw_report');	

		} else if ( $view == 'view' ){
		   $data = array();
		   $date1 = $request->date1;
		   $date2 = $request->date2;

		   
		
	   		$data['report_data'] = Withdraw::whereBetween('created_at', [$date1, $date2])
                                           ->orderBy('id', 'desc')
                                           ->get(); 

		   
		   $data['date1'] = $request->date1;
		   $data['date2'] = $request->date2;
		   
		   return view('backend.reports.withdraw_report',$data);	
		 }
    }

    public function wire_transfer_report( Request $request, $view = '' ){
		
		if( $view == '' ){

		    return view('backend.reports.wire_transfer_report');	

		} else if ( $view == 'view' ){
		   $data = array();
		   $date1 = $request->date1;
		   $date2 = $request->date2;
		   $status = $request->status;
		   $account = $request->account;

		   
		   if( $status == "all" ){			
		   		$data['report_data'] = Transaction::where('account_id', $account)
		   		                                  ->where('type', 'wire_transfer')
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }else{
			   $data['report_data'] = Transaction::where('account_id', $account)
		   		                                  ->where('type', 'wire_transfer')
		   		                                  ->where('status', $status)
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }
		   
		   $data['status'] = $request->status;
		   $data['date1'] = $request->date1;
		   $data['date2'] = $request->date2;
		   $data['account'] = $request->account;
		   $data['acc'] = Account::find($account);
		   
		   return view('backend.reports.wire_transfer_report',$data);	
		 }
    }

}	
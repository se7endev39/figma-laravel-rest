<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Transaction;
use App\Account;
use DB;
use Auth;

class ClientReportController extends Controller
{
	
	public function __construct()
    {
		date_default_timezone_set( get_option('timezone', get_option('timezone','Asia/Dhaka')) );	
	}

	 public function account_statement(Request $request, $view=""){
		
		if( $view == '' ){

		    return view('backend.user_panel.reports.account_statement');	

		} else if ( $view == 'view' ){
		   $data = array();
		   $date1 = $request->date1;
		   $date2 = $request->date2;
		   $status = $request->status;
		   $account = $request->account;

		   
		   if( $status == "all" ){			
		   		$data['report_data'] = Transaction::where('account_id', $account)
		   		                                  ->where('user_id', Auth::id())
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }else{
			   $data['report_data'] = Transaction::where('account_id', $account)
		   		                                  ->where('user_id', Auth::id())
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
		   
		   return view('backend.user_panel.reports.account_statement',$data);	
		 }
    }


    public function all_transaction(Request $request, $view = ''){
		
		if( $view == '' ){
		    return view('backend.user_panel.reports.all_transaction');	
		} else if ( $view == 'view' ){
		   $data = array();
		   $date1 = $request->date1;
		   $date2 = $request->date2;
 		   $type = $request->type;
 		   $status = $request->status;
		   
		
	   	   if( $status == "all" ){			
		   		$data['report_data'] = Transaction::where('user_id', Auth::id())
										   		  ->when($type, function ($query, $type) {
										   		  	 if($type != 'all'){
									                     return $query->where('type', $type);
									                 }
								                  })
		   		                                  ->whereBetween('created_at', [$date1, $date2])
                                              	  ->orderBy('id', 'desc')
                                                  ->get(); 
		   }else{
			   $data['report_data'] = Transaction::where('user_id', Auth::id())
			                                      ->when($type, function ($query, $type) {
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
		   
		   return view('backend.user_panel.reports.all_transaction', $data);	
		 }
    }

    public function referred_users(Request $request){
    	$data = array();
    	$data['report_data'] = \App\User::where('refer_user_id',Auth::id())
    	                                ->orderBy('id','desc')
    	                                ->get();
 		return view('backend.user_panel.reports.referred_users', $data);
    }

}	
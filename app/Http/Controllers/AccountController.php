<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Transaction;
use App\Deposit;
use App\Withdraw;
use Validator;
use Illuminate\Validation\Rule;
use Auth;
use DB;

class AccountController extends Controller
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
        $user_type = Auth::user()->user_type;

        $accounts = Account::select('accounts.*',DB::raw("((SELECT IFNULL(SUM(amount),0) 
                           FROM transactions WHERE dr_cr = 'cr' AND status = 'complete' AND account_id = accounts.id) - 
                           (SELECT IFNULL(SUM(amount),0) FROM transactions WHERE dr_cr = 'dr' 
                           AND status != 'reject' AND account_id = accounts.id)) as balance"))
                           ->when($user_type, function ($query, $user_type) {
                              if($user_type == 'staff'){
                                 return $query->where('accounts.created_by', Auth::id());
                              }
                           })
                           ->orderBy('id','desc')
                           ->get();
        return view('backend.account.list',compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.account.create');
		}else{
           return view('backend.account.modal.create');
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
        @ini_set('max_execution_time', 0);
        @set_time_limit(0);
        
		$validator = Validator::make($request->all(), [
			'account_number' => 'required|max:50|unique:accounts',
			'user_id' => 'required',
			'account_type_id' => 'required',
			'status' => 'required',
			'opening_balance' => 'required|numeric',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('accounts.create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
	    
		DB::beginTransaction();

        $account = new Account();
	    $account->account_number = $request->input('account_number');
		$account->user_id = $request->input('user_id');
		$account->account_type_id = $request->input('account_type_id');
		$account->status = $request->input('status');
		$account->opening_balance = $request->input('opening_balance');
		$account->description = $request->input('description');
		$account->created_by = Auth::id();
		$account->updated_by = Auth::id();
	
        $account->save();

        //Create Entry to Deposit Table
        $deposit = new Deposit();
	    $deposit->method = 'Manual';
	    $deposit->type = 'deposit';
		$deposit->amount = $account->opening_balance;
		$deposit->account_id = $account->id;
		$deposit->note = _lang('Account Opening Balance');
		$deposit->status = 1;
		$deposit->user_id = $request->input('user_id');
	
        $deposit->save();

		//Create Entry to Transaction Table
		$transaction = new Transaction();
	    $transaction->user_id = $request->input('user_id');
		$transaction->amount = $account->opening_balance;
		$transaction->account_id = $account->id;
		$transaction->dr_cr = 'cr';
		$transaction->type = 'deposit';
		$transaction->status = 'complete';
		$transaction->note = _lang('Account Opening Balance');
		$transaction->ref_id = $deposit->id;
		$transaction->created_by = Auth::id();
		$transaction->updated_by = Auth::id();
	
        $transaction->save();

        update_option( 'next_account_number', ( (int) get_option('next_account_number') + 1) );

        DB::commit();

        //Prefix Data
        $account->user_id = "<a href='" . action('UserController@show', $account->owner->id) . "' class='ajax-modal' data-title= '". _lang('View User Details') ."'>" . $account->owner->first_name." ".$account->owner->last_name. "</a>";
        $account->opening_balance = decimalPlace($account->opening_balance);
        $account->current_balance = $account->opening_balance;
        $account->account_type_id = $account->account_type->account_type.' ('.$account->account_type->currency->name.')';
        $account->status = $account->status == 1 ? _lang('Active') : _lang('Blocked');

        
		if(! $request->ajax()){
           return redirect()->route('accounts.create')->with('success', _lang('Saved Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$account]);
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
        $user_type = Auth::user()->user_type;
        $account = Account::where('id',$id)
                          ->when($user_type, function ($query, $user_type) {
                                 if($user_type == 'staff'){
                                     return $query->where('created_by', Auth::id());
                                 }
                              })
                          ->first();
		if(! $request->ajax()){
		    return view('backend.account.view',compact('account','id'));
		}else{
			return view('backend.account.modal.view',compact('account','id'));
		} 
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $user_type = Auth::user()->user_type;

        $account = Account::where('id',$id)
                          ->when($user_type, function ($query, $user_type) {
                                 if($user_type == 'staff'){
                                     return $query->where('created_by', Auth::id());
                                 }
                              })
                          ->first();
		if(! $request->ajax()){
		   return view('backend.account.edit',compact('account','id'));
		}else{
           return view('backend.account.modal.edit',compact('account','id'));
		}  
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$validator = Validator::make($request->all(), [
			'account_number' => [
                'required',
                'max:50',
                Rule::unique('accounts')->ignore($id),
            ],
			'user_id' => 'required',
			'account_type_id' => 'required',
			'status' => 'required',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('accounts.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		$user_type = Auth::user()->user_type;

        $account = Account::where('id',$id)
                          ->when($user_type, function ($query, $user_type) {
                                 if($user_type == 'staff'){
                                     return $query->where('created_by', Auth::id());
                                 }
                              })
                          ->first();

		$account->account_number = $request->input('account_number');
		$account->user_id = $request->input('user_id');
		$account->account_type_id = $request->input('account_type_id');
		$account->status = $request->input('status');
		//$account->opening_balance = $request->input('opening_balance');
		$account->description = $request->input('description');
		$account->updated_by = Auth::id();
	
        $account->save();


         //Prefix Data
        $account->user_id = "<a href='" . action('UserController@show', $account->owner->id) . "' class='ajax-modal' data-title= '". _lang('View User Details') ."'>" . $account->owner->first_name." ".$account->owner->last_name. "</a>";
        $account->opening_balance = decimalPlace($account->opening_balance);
        $account->current_balance = $account->opening_balance;
        $account->account_type_id = $account->account_type->account_type.' ('.$account->account_type->currency->name.')';
        $account->status = $account->status == 1 ? _lang('Active') : _lang('Blocked');
		
		if(! $request->ajax()){
           return redirect()->route('accounts.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$account]);
		}
	    
    }

     /**
     * Return Accounts by user id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_by_user_id( $user_id ){
        /*$accounts = Account::where('user_id',$user_id)
                           ->where('status',1)
                           ->with('account_type.currency')->get();
        */                   
        $accounts = Account::select('accounts.*',DB::raw("((SELECT IFNULL(SUM(amount),0) 
                           FROM transactions WHERE dr_cr = 'cr' AND status = 'complete' AND account_id = accounts.id) - 
                           (SELECT IFNULL(SUM(amount),0) FROM transactions WHERE dr_cr = 'dr' 
                           AND status != 'reject' AND account_id = accounts.id)) as balance"))
                           ->where('user_id',$user_id)
                           ->where('status',1)
                           ->with('account_type.currency')->get();                 
        echo json_encode($accounts);
    }
	
	public function get_by_account_type( $account_type_id ){
        $accounts = Account::where('account_type_id',$account_type_id)
                           ->where('status',1)
                           ->get();
                                          
        echo json_encode($accounts);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        if(Auth::user()->user_type != 'admin'){
            return back()->with('error',_lang('Permission denied !'));
        }

        DB::beginTransaction();
        
        $user_type = Auth::user()->user_type;

        $account = Account::where('id',$id)
                          ->when($user_type, function ($query, $user_type) {
                                 if($user_type == 'staff'){
                                     return $query->where('created_by', Auth::id());
                                 }
                            });
        if($account){                  
            $account->delete();

            $transaction = Transaction::where('account_id',$id);
            $transaction->delete();

            $deposit = Deposit::where('account_id',$id);
            $deposit->delete();

            $withdraw = Withdraw::where('account_id',$id);
            $withdraw->delete();
        }

        DB::commit();
        return redirect()->route('accounts.index')->with('success',_lang('Deleted Sucessfully'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Withdraw;
use App\Transaction;
use Validator;
use Illuminate\Validation\Rule;
use Auth;
use DB;

class WithdrawController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdraws = Withdraw::all()->sortByDesc("id");
        return view('backend.withdraw.list',compact('withdraws'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.withdraw.create');
		}else{
           return view('backend.withdraw.modal.create');
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
			'method' => '',
			'amount' => 'required|numeric',
			'account_id' => 'required',
			'user_id' => 'required'
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('withdraw.create')
							->withErrors($validator)
							->withInput();
			}			
		}
		
		//Check Available Balance
		if(get_account_balance( $request->account_id, $request->user_id) < $request->amount){	
			if(! $request->ajax()){
	           return back()->with('error', _lang('Insufficient balance !'));
	        }else{
			   return response()->json(['result'=>'error','message'=>_lang('Insufficient balance !')]);
			}
		}	
	    
		DB::beginTransaction();
        $withdraw = new Withdraw();
	    $withdraw->method = 'Manual';
		$withdraw->amount = $request->input('amount');
		$withdraw->account_id = $request->input('account_id');
		$withdraw->note = $request->input('note');
		$withdraw->status = 1;
		$withdraw->user_id = $request->input('user_id');
	
        $withdraw->save();
		
		//Create Transaction
		$transaction = new Transaction();
	    $transaction->user_id = $request->input('user_id');
		$transaction->amount = $request->input('amount');
		$transaction->account_id = $request->input('account_id');
		$transaction->dr_cr = 'dr';
		$transaction->type = 'withdraw';
		$transaction->status = 'complete';
		$transaction->note = $request->input('note');
		$transaction->ref_id = $withdraw->id;
		$transaction->created_by = Auth::user()->id;
		$transaction->updated_by = Auth::user()->id;
	
        $transaction->save();

        //Send Message Notification
		/*$message_object = new \stdClass();
		$message_object->first_name = $transaction->user->first_name;
		$message_object->last_name = $transaction->user->last_name;
		$message_object->account = $transaction->account->account_number;
		$message_object->currency = $transaction->account->account_type->currency->name;
		$message_object->amount = $transaction->amount;
		$message_object->date = $transaction->created_at->toDateTimeString();

		send_message($request->user_id, get_option('withdraw_subject'), get_option('withdraw_message'), $message_object);
		*/
		
		//Registering Event
		event(new \App\Events\WithdrawMoney($transaction));
		
        DB::commit();
		
		//Prefix Output
		if($withdraw->status == 0){
			$withdraw->status = "<span class='badge badge-warning'>"._lang('Pending')."</span>";
		}else if($withdraw->status == 1){
			$withdraw->status = "<span class='badge badge-success'>"._lang('Completed')."</span>";
		}else if($withdraw->status == 2){
			$withdraw->status = "<span class='badge badge-danger'>"._lang('Canceled')."</span>";
		}
		
		$withdraw->account_id = $withdraw->account->account_number.' ('.$withdraw->account->account_type->currency->name.')';
		$withdraw->user_id = $withdraw->user->first_name.' '.$withdraw->user->last_name;
		$withdraw->amount = decimalPlace($withdraw->amount);
		
        
		if(! $request->ajax()){
           return redirect()->route('withdraw.create')->with('success', _lang('Withdraw made sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Withdraw made sucessfully'),'data'=>$withdraw]);
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
        $withdraw = Withdraw::find($id);
		if(! $request->ajax()){
		    return view('backend.withdraw.view',compact('withdraw','id'));
		}else{
			return view('backend.withdraw.modal.view',compact('withdraw','id'));
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
        $withdraw = Withdraw::find($id);
		if(! $request->ajax()){
		   return view('backend.withdraw.edit',compact('withdraw','id'));
		}else{
           return view('backend.withdraw.modal.edit',compact('withdraw','id'));
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

    	@ini_set('max_execution_time', 0);
		@set_time_limit(0);
		
		$validator = Validator::make($request->all(), [
			'method' => '',
			'amount' => 'required|numeric',
			//'account_id' => 'required',
			//'user_id' => 'required'
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('withdraw.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
	    
        
		DB::beginTransaction();

        $withdraw = Withdraw::find($id);

        if ( $request->amount > $withdraw->amount ){

	        //Check Available Balance
			if(get_account_balance( $withdraw->account_id, $withdraw->user_id) < ($request->amount - $withdraw->amount)){
				if(! $request->ajax()){
		           return back()->with('error', _lang('Insufficient balance !'));
		        }else{
				   return response()->json(['result'=>'error','message'=>_lang('Insufficient balance !')]);
				}
			}

		}

		//$withdraw->method = $request->input('method');
		$withdraw->amount = $request->input('amount');
		//$withdraw->account_id = $request->input('account_id');
		$withdraw->note = $request->input('note');
		$withdraw->status = $request->input('status');
		//$withdraw->user_id = $request->input('user_id');
	
        $withdraw->save();
		
		//Update Transaction
		$transaction = Transaction::where('ref_id',$id)
								  ->where('type','withdraw')->first();
		//$transaction->user_id = $request->input('user_id');
		$transaction->amount = $request->input('amount');
		//$transaction->account_id = $request->input('account_id');
		$transaction->dr_cr = 'dr';
		$transaction->type = 'withdraw';
		
		if($request->input('status') == 0){
			$transaction->status = 'pending';
		}else if($request->input('status') == 1){
			$transaction->status = 'complete';
		}else if($request->input('status') == 2){
			$transaction->status = 'cancel';
		}
		
		$transaction->note = $request->input('note');
		$transaction->updated_by = Auth::user()->id;
	
        $transaction->save();

        DB::commit();
		
		//Prefix Output
		if($withdraw->status == 0){
			$withdraw->status = "<span class='badge badge-warning'>"._lang('Pending')."</span>";
		}else if($withdraw->status == 1){
			$withdraw->status = "<span class='badge badge-success'>"._lang('Completed')."</span>";
		}else if($withdraw->status == 2){
			$withdraw->status = "<span class='badge badge-danger'>"._lang('Canceled')."</span>";
		}
		

		$withdraw->account_id = $withdraw->account->account_number.' ('.$withdraw->account->account_type->currency->name.')';
		$withdraw->user_id = $withdraw->user->first_name.' '.$withdraw->user->last_name;
		$withdraw->amount = decimalPlace($withdraw->amount);
		
		
		if(! $request->ajax()){
           return redirect()->route('withdraw.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$withdraw]);
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
    	if(Auth::user()->user_type != 'admin'){
    		return back()->with('error',_lang('Permission denied !'));
    	}

    	DB::beginTransaction();
		//Delete Withdraw
        $withdraw = Withdraw::find($id);
        $withdraw->delete();
		
		//Delete Transaction
		$transaction = Transaction::where('ref_id',$id)
		                          ->where('dr_cr','dr');
		$transaction->delete();

		DB::commit();
		
        return redirect()->route('withdraw.index')->with('success',_lang('Deleted Sucessfully'));
    }
}

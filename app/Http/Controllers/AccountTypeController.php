<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountType;
use Validator;
use Illuminate\Validation\Rule;

class AccountTypeController extends Controller
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
        $accounttypes = AccountType::all()->sortByDesc("id");
        return view('backend.account_type.list',compact('accounttypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.account_type.create');
		}else{
           return view('backend.account_type.modal.create');
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
			'account_type'     => 'required|max:50',
			'currency_id'      => 'required',
			'maintenance_fee'  => 'required|numeric',
			'interest_rate'    => 'required|numeric',
			'interest_period'  => 'required',
			'payout_period'    => 'required',
			'auto_create'      => 'required',
			'tba_fee'          => 'required',
			'tba_fee_type'     => 'required',
			'tbu_fee'          => 'required',
			'tbu_fee_type'     => 'required',
			'cft_fee' 		   => 'required',
			'cft_fee_type'     => 'required',
			'owt_fee'          => 'required',
			'owt_fee_type'     => 'required',
			'iwt_fee'          => 'required',
			'iwt_fee_type'     => 'required',
			'payment_fee' 	   => 'required',
			'payment_fee_type' => 'required',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('account_types.create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
	    	
        $accounttype = new AccountType();
	    $accounttype->account_type = $request->account_type;
		$accounttype->currency_id = $request->currency_id;
		$accounttype->maintenance_fee = $request->maintenance_fee;
		$accounttype->interest_rate = $request->interest_rate;
		$accounttype->interest_period = $request->interest_period;
		$accounttype->payout_period = $request->payout_period;
		$accounttype->auto_create = $request->auto_create;
		$accounttype->description = $request->description;
		$accounttype->tba_fee = $request->tba_fee; 
		$accounttype->tba_fee_type = $request->tba_fee_type;  
		$accounttype->tbu_fee = $request->tbu_fee;       
		$accounttype->tbu_fee_type = $request->tbu_fee_type;  
		$accounttype->cft_fee = $request->cft_fee;		 
		$accounttype->cft_fee_type = $request->cft_fee_type;  
		$accounttype->owt_fee = $request->owt_fee;       
		$accounttype->owt_fee_type = $request->owt_fee_type; 
		$accounttype->iwt_fee = $request->iwt_fee;       
		$accounttype->iwt_fee_type = $request->iwt_fee_type;  
		$accounttype->payment_fee = $request->payment_fee;	 
		$accounttype->payment_fee_type = $request->payment_fee_type;
	
        $accounttype->save();

        //Prefix Data
        $accounttype->currency_id = $accounttype->currency->name;
        $accounttype->maintenance_fee = decimalPlace($accounttype->maintenance_fee);
		$accounttype->interest_rate = decimalPlace($accounttype->interest_rate);
        $accounttype->auto_create = $request->input('auto_create') == 1 ? _lang('Yes') : _lang('No');
        
		if(! $request->ajax()){
           return redirect()->route('account_types.create')->with('success', _lang('Saved Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$accounttype]);
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
        $accounttype = AccountType::find($id);
		if(! $request->ajax()){
		    return view('backend.account_type.view',compact('accounttype','id'));
		}else{
			return view('backend.account_type.modal.view',compact('accounttype','id'));
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
        $accounttype = AccountType::find($id);
		if(! $request->ajax()){
		   return view('backend.account_type.edit',compact('accounttype','id'));
		}else{
           return view('backend.account_type.modal.edit',compact('accounttype','id'));
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
			'account_type'     => 'required|max:50',
			'currency_id'      => 'required',
			'maintenance_fee'  => 'required|numeric',
			'interest_rate'    => 'required|numeric',
			'interest_period'  => 'required',
			'payout_period'    => 'required',
			'auto_create'      => 'required',
			'tba_fee'          => 'required',
			'tba_fee_type'     => 'required',
			'tbu_fee'          => 'required',
			'tbu_fee_type'     => 'required',
			'cft_fee' 		   => 'required',
			'cft_fee_type'     => 'required',
			'owt_fee'          => 'required',
			'owt_fee_type'     => 'required',
			'iwt_fee'          => 'required',
			'iwt_fee_type'     => 'required',
			'payment_fee' 	   => 'required',
			'payment_fee_type' => 'required',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('account_types.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $accounttype = AccountType::find($id);
		$accounttype->account_type = $request->account_type;
		$accounttype->currency_id = $request->currency_id;
		$accounttype->maintenance_fee = $request->maintenance_fee;
		$accounttype->interest_rate = $request->interest_rate;
		$accounttype->interest_period = $request->interest_period;
		$accounttype->payout_period = $request->payout_period;
		$accounttype->auto_create = $request->auto_create;
		$accounttype->description = $request->description;
		$accounttype->tba_fee = $request->tba_fee; 
		$accounttype->tba_fee_type = $request->tba_fee_type;  
		$accounttype->tbu_fee = $request->tbu_fee;       
		$accounttype->tbu_fee_type = $request->tbu_fee_type;  
		$accounttype->cft_fee = $request->cft_fee;		 
		$accounttype->cft_fee_type = $request->cft_fee_type;  
		$accounttype->owt_fee = $request->owt_fee;       
		$accounttype->owt_fee_type = $request->owt_fee_type; 
		$accounttype->iwt_fee = $request->iwt_fee;       
		$accounttype->iwt_fee_type = $request->iwt_fee_type;  
		$accounttype->payment_fee = $request->payment_fee;	 
		$accounttype->payment_fee_type = $request->payment_fee_type;
	
        $accounttype->save();

        //Prefix Data
        $accounttype->currency_id = $accounttype->currency->name;
        $accounttype->maintenance_fee = decimalPlace($accounttype->maintenance_fee);
		$accounttype->interest_rate = decimalPlace($accounttype->interest_rate);
        $accounttype->auto_create = $request->input('auto_create') == 1 ? _lang('Yes') : _lang('No');
		
		if(! $request->ajax()){
           return redirect()->route('account_types.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$accounttype]);
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
        $accounttype = AccountType::find($id);
        $accounttype->delete();
        return redirect()->route('account_types.index')->with('success',_lang('Deleted Sucessfully'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use Validator;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencys = Currency::orderBy("base_currency","desc")->get();
        return view('backend.currency.list',compact('currencys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.currency.create');
		}else{
           return view('backend.currency.modal.create');
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
			'name' => 'required|max:3|min:3',
			'base_currency' => 'required|integer',
			'exchange_rate' => 'required|numeric',
			'status' => 'required|integer'
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect('admin/currency/create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
	    
		if($request->input('base_currency') == '1'){
			$currency = Currency::where('base_currency','1')->first();
			if($currency){
				$currency->base_currency = 0;
				$currency->save();
			}
		}
		
        $currency = new Currency();
	    $currency->name = $request->input('name');
		$currency->base_currency = $request->input('base_currency');
		$currency->exchange_rate = $request->input('exchange_rate');
		$currency->status = $request->input('status');
	
        $currency->save();
        
        //Update Exchange Rate
        update_currency_exchange_rate(true);
		
		//Prefix Output
		$currency->base_currency = $currency->base_currency == '1' ? status(_lang('Yes'), 'primary') : status(_lang('No'), 'danger');
		$currency->status = $currency->status == '1' ? status(_lang('Active'), 'success') : status(_lang('In-Active'), 'danger');
	
        
		if(! $request->ajax()){
           return redirect('admin/currency/create')->with('success', _lang('Saved Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$currency]);
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
        $currency = Currency::find($id);
		if(! $request->ajax()){
		    return view('backend.currency.view',compact('currency','id'));
		}else{
			return view('backend.currency.modal.view',compact('currency','id'));
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
        $currency = Currency::find($id);
		if(! $request->ajax()){
		   return view('backend.currency.edit',compact('currency','id'));
		}else{
           return view('backend.currency.modal.edit',compact('currency','id'));
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
			'name' => 'required|max:3|min:3',
			'base_currency' => 'required|integer',
			'exchange_rate' => 'required|numeric',
			'status' => 'required|integer'
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('currency.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        if($request->input('base_currency') == '1'){
			$base_currency = Currency::where('base_currency','1')->first();
			if($base_currency){
				$base_currency->base_currency = 0;
				$base_currency->save();
			}
		}	
		
        $currency = Currency::find($id);
		$currency->name = $request->input('name');
		$currency->base_currency = $request->input('base_currency');
		$currency->exchange_rate = $request->input('exchange_rate');
		$currency->status = $request->input('status');
	
        $currency->save();

        //Update Exchange Rate
        update_currency_exchange_rate(true);
		
		//Prefix Output
		$currency->base_currency = $currency->base_currency == '1' ? status(_lang('Yes'), 'primary') : status(_lang('No'), 'danger');
		$currency->status = $currency->status == '1' ? status(_lang('Active'), 'success') : status(_lang('In-Active'), 'danger');
	
		
		if(! $request->ajax()){
           return redirect()->route('currency.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$currency]);
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
        $currency = Currency::find($id);
        $currency->delete();
        return redirect('admin/currency')->with('success',_lang('Deleted Sucessfully'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CardTransaction;
use Validator;
use Illuminate\Validation\Rule;
use Auth;

class CardTransactionController extends Controller
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
        $cardtransactions = CardTransaction::all()->sortByDesc("id");
        return view('backend.card_transaction.list',compact('cardtransactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.card_transaction.create');
		}else{
           return view('backend.card_transaction.modal.create');
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
			'card_id' => 'required',
			'dr_cr' => 'required',
			'amount' => 'required|numeric',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('card_transactions.create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
	    
		
        $cardtransaction = new CardTransaction();
	    $cardtransaction->card_id = $request->input('card_id');
		$cardtransaction->dr_cr = $request->input('dr_cr');
		$cardtransaction->amount = $request->input('amount');
		$cardtransaction->note = $request->input('note');
		$cardtransaction->status = $request->input('status');
		$cardtransaction->created_by = Auth::id();
		$cardtransaction->updated_by = Auth::id();
	
        $cardtransaction->save();

        //Prefix Output
		$cardtransaction->card_id = $cardtransaction->card->card_number.' - '.$cardtransaction->card->card_type->card_type;
		$cardtransaction->dr_cr = $cardtransaction->dr_cr == 'dr' ? _lang('Debit') : _lang('Credit');
		$cardtransaction->amount = $cardtransaction->card->card_type->currency->name.' '.decimalPlace($cardtransaction->amount);
		if($cardtransaction->status == 0){
			$cardtransaction->status = "<span class='badge badge-warning'>{{ _lang('Pending') }}</span>";
		}elseif($cardtransaction->status == 1){
			$cardtransaction->status = "<span class='badge badge-success'>{{ _lang('Completed') }}</span>";
		}elseif($cardtransaction->status == 2){
			$cardtransaction->status = "<span class='badge badge-danger'>{{ _lang('Rejected') }}</span>";
		}
		

		if(! $request->ajax()){
           return redirect()->route('card_transactions.create')->with('success', _lang('Saved Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$cardtransaction]);
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
        $cardtransaction = CardTransaction::find($id);
		if(! $request->ajax()){
		    return view('backend.card_transaction.view',compact('cardtransaction','id'));
		}else{
			return view('backend.card_transaction.modal.view',compact('cardtransaction','id'));
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
        $cardtransaction = CardTransaction::find($id);
		if(! $request->ajax()){
		   return view('backend.card_transaction.edit',compact('cardtransaction','id'));
		}else{
           return view('backend.card_transaction.modal.edit',compact('cardtransaction','id'));
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
			'card_id' => 'required',
			'dr_cr' => 'required',
			'amount' => 'required|numeric',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('card_transactions.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $cardtransaction = CardTransaction::find($id);
		$cardtransaction->card_id = $request->input('card_id');
		$cardtransaction->dr_cr = $request->input('dr_cr');
		$cardtransaction->amount = $request->input('amount');
		$cardtransaction->note = $request->input('note');
		$cardtransaction->status = $request->input('status');
		$cardtransaction->updated_by = Auth::id;
	
        $cardtransaction->save();

        //Prefix Output
		$cardtransaction->card_id = $cardtransaction->card->card_number.' - '.$cardtransaction->card->card_type->card_type;
		$cardtransaction->dr_cr = $cardtransaction->dr_cr == 'dr' ? _lang('Debit') : _lang('Credit');
		$cardtransaction->amount = $cardtransaction->card->card_type->currency->name.' '.decimalPlace($cardtransaction->amount);
		if($cardtransaction->status == 0){
			$cardtransaction->status = "<span class='badge badge-warning'>{{ _lang('Pending') }}</span>";
		}elseif($cardtransaction->status == 1){
			$cardtransaction->status = "<span class='badge badge-success'>{{ _lang('Completed') }}</span>";
		}elseif($cardtransaction->status == 2){
			$cardtransaction->status = "<span class='badge badge-danger'>{{ _lang('Rejected') }}</span>";
		}
		
		if(! $request->ajax()){
           return redirect()->route('card_transactions.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$cardtransaction]);
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
        $cardtransaction = CardTransaction::find($id);
        $cardtransaction->delete();
        return redirect()->route('card_transactions.index')->with('success',_lang('Deleted Sucessfully'));
    }
}

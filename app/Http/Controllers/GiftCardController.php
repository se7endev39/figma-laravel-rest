<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GiftCard;
use App\Transaction;
use Validator;
use Illuminate\Validation\Rule;
use DB;
use Auth;

class GiftCardController extends Controller
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
    public function index($status = 'active_gift_card')
    {
    	$status = $status == 'active_gift_card' ? 1 : 0;

    	if( $status == 1 ){
    		$title = _lang('Active Gift Card');
    	}else if( $status == 0 ){
			$title = _lang('Used Gift Card');
    	}

        $giftcards = GiftCard::where('created_by',Auth::id())
                             ->where('status', $status)
                             ->orderBy('id', 'desc')
							 ->get();
        return view('backend.gift_card.list', compact('giftcards','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.gift_card.create');
		}else{
           return view('backend.gift_card.modal.create');
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
			'currency_id' => 'required',
			'debit_account' => Auth::user()->user_type == 'user' ? 'required' : 'nullable',
			'amount' => 'required|numeric',
			'code' => 'required',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('gift_cards.create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
	    DB::beginTransaction();
        
		if(Auth::user()->user_type == 'user'){
			$currency = \App\Currency::find($request->currency_id)->name;
			$amount = convert_currency($currency, account_currency($request->debit_account), $request->amount);
					
			//Check available Balance
			if(get_account_balance($request->debit_account) < $amount){
				return back()->with('error', _lang('Insufficient balance !'));
			}

			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = Auth::id();
			$debit->amount = $amount;
			$debit->account_id = $request->debit_account;
			$debit->dr_cr = 'dr';
			$debit->type = 'payment';
			$debit->status = 'complete';
			$debit->note = _lang('Create Gift Card');
			$debit->created_by = Auth::id();
			$debit->updated_by = Auth::id();
			$debit->save();
		}	

		
        $giftcard = new GiftCard();
	    $giftcard->currency_id = $request->input('currency_id');
		
		if(Auth::user()->user_type == 'user'){
			$giftcard->transaction_id = $debit->id;
		}
		
		$giftcard->amount = $request->input('amount');
		$giftcard->code = $request->input('code');
		$giftcard->status = 1;
		$giftcard->created_by = Auth::id();
	
        $giftcard->save();

        DB::commit();

		if($giftcard->id > 0){
			return back()->with('success', _lang('New Gift Card Created Sucessfully.'));
	    }else{
	    	return back()->with('error', _lang('Error Occured, Please try again !'));
	    }
        
   }
	

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $giftcard = GiftCard::where('id', $id)
		                    ->where('created_by', Auth::user()->id)
							->first();
		if(! $request->ajax()){
		    return view('backend.gift_card.view',compact('giftcard','id'));
		}else{
			return view('backend.gift_card.modal.view',compact('giftcard','id'));
		} 
        
    }

    /**
     * Redeem Gift Card.
     */
    public function redeem(Request $request){
        if (! $request->isMethod('post')){
            //Show Redeem Form
            return view('backend.gift_card.redeem');
        }else{
			$validator = Validator::make($request->all(), [
				'credit_account' => 'required',
				'code' => 'required',
			]);
			
			if ($validator->fails()) {
				if($request->ajax()){ 
					return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
				}else{
					return redirect()->route('gift_cards.create')
								->withErrors($validator)
								->withInput();
				}			
			}
			
			$gift_card = GiftCard::where('code',$request->code)
			                ->where('created_by', '!=', Auth::id())
			                ->where('status', 1)
							->first();
			if( $gift_card ){
				DB::beginTransaction();
					//Make Credit Transaction
					$credit = new Transaction();
					$credit->user_id = Auth::id();
					$credit->amount = convert_currency($gift_card->currency->name, account_currency($request->credit_account), $gift_card->amount);
					$credit->account_id = $request->credit_account;
					$credit->dr_cr = 'cr';
					$credit->type = 'deposit';
					$credit->status = 'complete';
					$credit->note = _lang('Redeem Gift Card');
					$credit->created_by = Auth::id();
					$credit->updated_by = Auth::id();
					$credit->save();

					//Update Gift Card Status
					$gift_card->status = 0;
					$gift_card->redeem_by = Auth::id();
					$gift_card->redeem_date = date('Y-m-d H:i:s');
					$gift_card->save();


				DB::commit();

				if($credit->id > 0){
					return back()->with('success', _lang('Gift Card Redeem Sucessfully.'));
			    }else{
			    	return back()->with('error', _lang('Error Occured, Please try again !'));
			    }
			}else{
				return back()->with('error', _lang('Invalid or used gift card !'));
			}				
							
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
    	DB::beginTransaction();

        $giftcard = GiftCard::where('id',$id)
		                    ->where('created_by', Auth::user()->id)
		                    ->where('status',1)->first();
        $transaction = Transaction::find($giftcard->transaction_id);
        if( $transaction ){
			$transaction->delete();
		}
        $giftcard->delete();

        DB::commit();

        return redirect()->route('gift_cards.index')->with('success',_lang('Gift Card Removed Sucessfully'));
    }
}

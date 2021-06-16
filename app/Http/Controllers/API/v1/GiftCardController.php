<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\GiftCard;
use App\Transaction;
use Validator;
use DB;

class GiftCardController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $giftcards = GiftCard::where('created_by',auth('api')->user()->id)
		                     ->with('currency')
                             ->orderBy('id', 'desc')
							 ->paginate(10);
        return response()->json($giftcards, $this->successStatus);
    }
	
	 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
		$user = auth('api')->user();
		
		$validator = Validator::make($request->all(), [
			'currency_id' 	=> 'required',
			'debit_account' => 'required',
			'amount' 		=> 'required|numeric',
			//'code' 			=> 'required',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
			    
		
        DB::beginTransaction();
        
		if($user->user_type == 'user'){
			$currency = \App\Currency::find($request->currency_id)->name;
			$amount = convert_currency($currency, account_currency($request->debit_account), $request->amount);
					
			//Check available Balance
			if(get_account_balance($request->debit_account) < $amount){
				return response()->json(['result' => false, 'message' => _lang('Insufficient balance !')]);		
			}

			//Make Debit Transaction
			$debit = new Transaction();
			$debit->user_id = $user->id;
			$debit->amount = $amount;
			$debit->account_id = $request->debit_account;
			$debit->dr_cr = 'dr';
			$debit->type = 'payment';
			$debit->status = 'complete';
			$debit->note = _lang('Create Gift Card');
			$debit->created_by = $user->id;
			$debit->updated_by = $user->id;
			$debit->save();
		}	

		
        $giftcard = new GiftCard();
	    $giftcard->currency_id = $request->currency_id;
		
		if($user->user_type == 'user'){
			$giftcard->transaction_id = $debit->id;
		}
		
		$giftcard->amount = $request->amount;
		$giftcard->code = generate_gift_card();
		$giftcard->status = 1;
		$giftcard->created_by = $user->id;
	
        $giftcard->save();

        DB::commit();

		$data['result']  = true;
		$data['message'] = _lang('New Gift Card Created Sucessfully');
        $data['data']	 = $giftcard;
		
		return response()->json($data, $this->successStatus);
    
   }
	

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $giftcard = GiftCard::where('id', $id)
		                    ->where('created_by', auth('api')->user()->id)
							->first();
							
		$data['result'] =  true;
		$data['id'] =  $id;
        $data['data']= $giftcard;
		
		return response()->json($data, $this->successStatus); 
        
    }
	
	/**
     * Redeem Gift Card.
     */
    public function redeem(Request $request){
		
		$user = auth('api')->user();
		
		$validator = Validator::make($request->all(), [
			'credit_account' => 'required',
			'code' => 'required',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
		
		$gift_card = GiftCard::where('code',$request->code)
						->where('created_by', '!=', $user->id)
						->where('status', 1)
						->first();
						
		if( $gift_card ){
			DB::beginTransaction();
				//Make Credit Transaction
				$credit = new Transaction();
				$credit->user_id = $user->id;
				$credit->amount = convert_currency($gift_card->currency->name, account_currency($request->credit_account), $gift_card->amount);
				$credit->account_id = $request->credit_account;
				$credit->dr_cr = 'cr';
				$credit->type = 'deposit';
				$credit->status = 'complete';
				$credit->note = _lang('Redeem Gift Card');
				$credit->created_by = $user->id;
				$credit->updated_by = $user->id;
				$credit->save();

				//Update Gift Card Status
				$gift_card->status = 0;
				$gift_card->redeem_by = $user->id;
				$gift_card->redeem_date = date('Y-m-d H:i:s');
				$gift_card->save();

			DB::commit();

			if($credit->id > 0){
				$data['result']  = true;
				$data['data']  =  $gift_card;
				$data['message'] = _lang('Gift Card Redeem Sucessfully');
				return response()->json($data, $this->successStatus);
			}else{
				$data['result']  = false;
				$data['message'] = _lang('Error Occured, Please try again !');
				return response()->json($data, $this->successStatus);
			}
		}else{
			return response()->json(['result' => false, 'message' => _lang('Invalid or used gift card !')]);		
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
							->where('created_by', auth('api')->user()->id)
		                    ->where('status',1)->first();
							
        $transaction = Transaction::find($giftcard->transaction_id);
		
        if( $transaction ){
			$transaction->delete();
		}
        $giftcard->delete();

        DB::commit();
		
		$data['result'] =  true;
		$data['message'] =  _lang('Deleted Sucessfully');
	
		return response()->json($data, $this->successStatus);
    }

}
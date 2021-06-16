<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\Currency;
use Validator;
use DB;

class AccountController extends Controller
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
		$accounts = Account::join('account_types','accounts.account_type_id','account_types.id')
						   ->join('currency','account_types.currency_id','currency.id')
						   ->select('accounts.*','account_types.account_type','currency.name as currency',DB::raw("((SELECT IFNULL(SUM(amount),0) 
                             FROM transactions WHERE dr_cr = 'cr' AND status = 'complete' AND account_id = accounts.id) - 
                             (SELECT IFNULL(SUM(amount),0) FROM transactions WHERE dr_cr = 'dr' 
                             AND status != 'reject' AND account_id = accounts.id)) as balance"))
						   ->where('accounts.user_id', auth('api')->user()->id)
                           ->get();
		return response()->json($accounts, $this->successStatus);
    }
	
	 /**
     * Display a listing of cards
     *
     * @return \Illuminate\Http\Response
     */
    public function cards()
    {
		$cards= \App\Card::join('card_types','cards.card_type_id','card_types.id')
						 ->join('currency','card_types.currency_id','currency.id')
						 ->select('cards.*','card_types.card_type','currency.name as currency',DB::raw("((SELECT IFNULL(SUM(amount),0) 
						 FROM card_transactions WHERE dr_cr = 'cr' AND status = 1 AND card_id = cards.id) - 
						 (SELECT IFNULL(SUM(amount),0) FROM card_transactions WHERE dr_cr = 'dr' 
						 AND status = 1 AND card_id = cards.id)) as balance"))
						 ->where('cards.user_id', auth('api')->user()->id)
						 ->orderBy('id','desc')
						 ->get();
		return response()->json($cards, $this->successStatus);
    }
	
	public function accounts_cards()
    {
		$accounts = Account::join('account_types','accounts.account_type_id','account_types.id')
						   ->join('currency','account_types.currency_id','currency.id')
						   ->select('accounts.*','account_types.account_type','currency.name as currency',DB::raw("((SELECT IFNULL(SUM(amount),0) 
                             FROM transactions WHERE dr_cr = 'cr' AND status = 'complete' AND account_id = accounts.id) - 
                             (SELECT IFNULL(SUM(amount),0) FROM transactions WHERE dr_cr = 'dr' 
                             AND status != 'reject' AND account_id = accounts.id)) as balance"))
						   ->where('accounts.user_id', auth('api')->user()->id)
                           ->get();
						   
		$cards= \App\Card::join('card_types','cards.card_type_id','card_types.id')
						 ->join('currency','card_types.currency_id','currency.id')
						 ->select('cards.*','card_types.card_type','currency.name as currency',DB::raw("((SELECT IFNULL(SUM(amount),0) 
						 FROM card_transactions WHERE dr_cr = 'cr' AND status = 1 AND card_id = cards.id) - 
						 (SELECT IFNULL(SUM(amount),0) FROM card_transactions WHERE dr_cr = 'dr' 
						 AND status = 1 AND card_id = cards.id)) as balance"))
						 ->where('cards.user_id', auth('api')->user()->id)
						 ->orderBy('id','desc')
						 ->get();	
						 
		return response()->json(array('accounts' => $accounts, 'cards' => $cards), $this->successStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $account = Account::find($id);
		return response()->json($account, $this->successStatus);    
    }
	
	 public function currency_list(Request $request)
    {
        $currency = Currency::where('status',1)->get();
		return response()->json($currency, $this->successStatus);    
    }

}
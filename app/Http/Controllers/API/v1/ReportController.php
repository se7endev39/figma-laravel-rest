<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
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
    public function transactions()
    {
		
		$transactions = \App\Transaction::where('user_id',auth('api')->user()->id)
										->orderBy('id','desc')
										->with('account.account_type.currency')
                                        ->paginate(10);
		return response()->json($transactions, $this->successStatus);						   
    }
	

}
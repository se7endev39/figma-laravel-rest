<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PaymentRequest;
use App\Transaction;
use Validator;
use App\Utilities\Overrider;
use Illuminate\Validation\Rule;
use App\Notifications\PaymentRequest as RequestNotification;
use Auth;
use DB;

class PaymentRequestController extends Controller
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
        $paymentrequests = PaymentRequest::where('created_by', auth('api')->user()->id)
		                                 ->with('account.account_type.currency')
										 ->orderBy('id','desc')
										 ->paginate(10);
        return response()->json($paymentrequests, $this->successStatus);
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
			'account_id' 	   => 'required',
			'amount' 		   => 'required|numeric',
			'recipients_email' => 'required',
			'description'      => 'required',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
			    
		
        $paymentrequest = new PaymentRequest();
	    $paymentrequest->account_id = $request->input('account_id');
		$paymentrequest->amount = $request->input('amount');
		$paymentrequest->status = 'pending';
		$paymentrequest->description = $request->input('description');
		$paymentrequest->created_by = auth('api')->user()->id;
	
        $paymentrequest->save();
		
		//Send Email Notification
		Overrider::load("Settings");
		
		$user = new \App\User();
		$user->email = $request->recipients_email;
		$user->notify(new RequestNotification($paymentrequest));

		$data['result']  = true;
		$data['message'] = _lang('Payment Request Send Sucessfully');
        $data['data']	 = $paymentrequest;
		
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
        $paymentrequest = PaymentRequest::where('id', $id)
		                                ->where('created_by', auth('api')->user()->id)
										->first();
		$data['result'] =  true;
		$data['id'] =  $id;
        $data['data']= $paymentrequest;
		
		return response()->json($data, $this->successStatus); 
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$paymentrequest = PaymentRequest::where('id',$id)
		                                ->where('created_by', auth('api')->user()->id);
        $paymentrequest->delete();
		
		$data['result'] =  true;
		$data['message'] =  _lang('Deleted Sucessfully');
	
		return response()->json($data, $this->successStatus);

    }

}
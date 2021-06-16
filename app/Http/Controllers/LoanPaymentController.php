<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoanPayment;
use App\LoanRepayment;
use App\Transaction;
use App\Loan;
use Validator;
use DataTables;
use DB;
use Auth;

class LoanPaymentController extends Controller
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
        return view('backend.loan_payment.list');
    }
	
	public function get_table_data(){
		
		$loanpayments = LoanPayment::select('loan_payments.*')
                                   ->with('loan')
						           ->orderBy("loan_payments.id","desc");

		return Datatables::eloquent($loanpayments)
                        ->editColumn('paid_at', function ($loanpayment) {
                            return date('d/M/Y',strtotime($loanpayment->paid_at));
                        })
						->addColumn('action', function ($loanpayment) {
								return '<form action="'.action('LoanPaymentController@destroy', $loanpayment['id']).'" class="text-center" method="post">'
								.'<a href="'.action('LoanPaymentController@show', $loanpayment['id']).'" data-title="'. _lang('View Payment Details') .'" class="btn btn-primary btn-xs">'. _lang('View') .'</a>&nbsp;'
								.csrf_field()
								.'<input name="_method" type="hidden" value="DELETE">'
								.'<button class="btn btn-danger btn-xs btn-remove" type="submit">'._lang('Delete') .'</button>'
								.'</form>';
						})
						->setRowId(function ($loanpayment) {
							return "row_".$loanpayment->id;
						})
						->rawColumns(['action'])
						->make(true);							    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.loan_payment.create');
        }else{
           return view('backend.loan_payment.modal.create');
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
            'loan_id'        => 'required',
			'paid_at'        => 'required',
			'late_penalties' => 'nullable|numeric',
			'amount_to_pay'  => 'required|numeric',
			'account_id'     => 'required',
            'due_amount_of'  => 'required'
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('loan_payments.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }


        DB::beginTransaction();

        $repayment = LoanRepayment::find($request->due_amount_of);
        $loan = Loan::find($request->loan_id);

        //Create Transaction
        $base_amount = $repayment->amount_to_pay + $request->late_penalties;
        $amount = convert_currency(account_currency($loan->account_id), account_currency($request->account_id), $base_amount);

        //Check Available Balance
        if(get_account_balance($request->account_id, $loan->borrower_id) < $amount){
            return back()->with('error', _lang('Insufficient balance !'));
        }

        $transaction = new Transaction();
        $transaction->user_id = $repayment->loan->borrower_id;
        $transaction->amount = $amount;
        $transaction->account_id = $request->account_id; //Debit Account
        $transaction->dr_cr = 'dr';
        $transaction->type = 'loan_payment';
        $transaction->status = 'complete';
        $transaction->note = 'Loan Repayment';
        $transaction->loan_id = $loan->id;
        $transaction->created_by = Auth::id();
        $transaction->updated_by = Auth::id();
    
        $transaction->save();
	
        
        $loanpayment = new LoanPayment();
        $loanpayment->loan_id = $request->loan_id;
		$loanpayment->paid_at = $request->paid_at;
        $loanpayment->late_penalties = $request->late_penalties; //it's optionals
		$loanpayment->interest = $repayment->interest;
		$loanpayment->amount_to_pay = $repayment->amount_to_pay;
		$loanpayment->remarks = $request->remarks;
        $loanpayment->transaction_id = $transaction->id;
		$loanpayment->repayment_id = $repayment->id;
		$loanpayment->user_id = Auth::id();

        $loanpayment->save();

        //Update Loan Balance
        $repayment->status = 1;
        $repayment->save();

        
        $loan->total_paid = $loan->total_paid + $repayment->amount_to_pay;
        if($loan->total_paid >= $loan->applied_amount){
            $loan->status = 2;
        }
        $loan->save();

        DB::commit();

        if(! $request->ajax()){
           return redirect()->route('loan_payments.create')->with('success', _lang('Payment Made Sucessfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'), 'data'=>$loanpayment, 'table' => '#loan_payments_table']);
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
        $loanpayment = LoanPayment::find($id);
        if(! $request->ajax()){
            return view('backend.loan_payment.view',compact('loanpayment','id'));
        }else{
            return view('backend.loan_payment.modal.view',compact('loanpayment','id'));
        } 
        
    }


    public function get_repayment_by_loan_id( $loan_id ){                  
        $repayments = LoanRepayment::where('loan_id', $loan_id)
                                   ->where('status',0)
                                   ->get();                 
        echo json_encode($repayments);
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

        $loanpayment = LoanPayment::find($id);

        $transaction = Transaction::find($loanpayment->transaction_id);
        $transaction->delete();

        //Update Balance
        $repayment = LoanRepayment::find($loanpayment->repayment_id);
        $repayment->status = 0;
        $repayment->save();

        $loan = Loan::find($loanpayment->loan_id);
        $loan->total_paid = $loan->total_paid - $repayment->amount_to_pay;
        $loan->save();

        $loanpayment->delete();

        DB::commit();

        return back()->with('success',_lang('Deleted Sucessfully'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fee;
use App\Transaction;
use Validator;
use DataTables;
use DB;
use Auth;

class FeeController extends Controller
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
        return view('backend.fee.list');
    }
	
	public function get_table_data(){
		
		$fees = Fee::select('custom_fees.*')
				   ->orderBy("custom_fees.id","desc");

		return Datatables::eloquent($fees)
						->addColumn('action', function ($fee) {
								return '<form action="'.action('FeeController@destroy', $fee['id']).'" class="text-center" method="post">'
								.'<a href="'.action('FeeController@show', $fee['id']).'" class="btn btn-primary btn-xs">'. _lang('View') .'</a>&nbsp;'
								.csrf_field()
								.'<input name="_method" type="hidden" value="DELETE">'
								.'<button class="btn btn-danger btn-xs btn-remove" type="submit">'._lang('Delete') .'</button>'
								.'</form>';
						})
						->setRowId(function ($fee) {
							return "row_".$fee->id;
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
           return view('backend.fee.create');
        }else{
           return view('backend.fee.modal.create');
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
            'accounts' => 'required',
            'title' => 'required',
			'amount' => 'required|numeric',
			'note' => '',
        ]);
		

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('custom_fees.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        
        DB::beginTransaction();
		
        $fee = new Fee();
        $fee->title = $request->input('title');
		$fee->amount = $request->input('amount');
		$fee->note = $request->input('note');

        $fee->save();
		
		$created_by = Auth::id();
		
		foreach($request->accounts as $acc){
			$account = explode(",",$acc);
			
			$transaction = new Transaction();
			$transaction->user_id = $account[1];
			$transaction->amount = $fee->amount;
			$transaction->account_id = $account[0];
			$transaction->dr_cr = 'dr';
			$transaction->type = 'fee';
			$transaction->status = 'complete';
			$transaction->note = $request->input('note');
			$transaction->custom_fee_id = $fee->id;
			$transaction->created_by = $created_by;
			$transaction->updated_by = $created_by;
		
			$transaction->save();
		}

		DB::commit();
		
        if(! $request->ajax()){
           return redirect()->route('custom_fees.create')->with('success', _lang('Fee Deduct Sucessfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Fee Deduct Sucessfully'), 'data'=>$fee, 'table' => '#custom_fees_table']);
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
        $fee = Fee::find($id);
        if(! $request->ajax()){
            return view('backend.fee.view',compact('fee','id'));
        }else{
            return view('backend.fee.modal.view',compact('fee','id'));
        } 
        
    }


    /*public function edit(Request $request,$id)
    {
        $fee = Fee::find($id);
        if(! $request->ajax()){
            return view('backend.fee.edit',compact('fee','id'));
        }else{
            return view('backend.fee.modal.edit',compact('fee','id'));
        }  
        
    }

    public function update(Request $request, $id)
    {
		$validator = Validator::make($request->all(), [
			'title' => 'required',
			'amount' => 'required|numeric',
			'note' => '',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('custom_fees.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	

        $fee = Fee::find($id);
		$fee->title = $request->input('title');
		$fee->amount = $request->input('amount');
		$fee->note = $request->input('note');
	
        $fee->save();
		
		if(! $request->ajax()){
           return redirect()->route('custom_fees.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'), 'data'=>$fee, 'table' => '#custom_fees_table']);
		}
	    
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		DB::beginTransaction();
		
        $fee = Fee::find($id);
        $fee->delete();
		
		$transactions = Transaction::where('custom_fee_id',$id)
		                  ->where('type','fee');
        $transactions->delete();
		
		DB::commit();
		
        return redirect()->route('custom_fees.index')->with('success',_lang('Deleted Sucessfully'));
    }
}
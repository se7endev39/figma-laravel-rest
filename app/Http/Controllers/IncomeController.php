<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FinanceTransaction;
use Validator;
use DataTables;

class IncomeController extends Controller
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
        return view('backend.accounting.income.list');
    }
	
	public function get_table_data(){
		
		$financetransactions = FinanceTransaction::select('finance_transactions.*')
                                                 ->with('category')
                                                 ->where('type','income')
						                         ->orderBy("finance_transactions.id","desc");

		return Datatables::eloquent($financetransactions)
						->addColumn('action', function ($financetransaction) {
								return '<form action="'.action('IncomeController@destroy', $financetransaction['id']).'" class="text-center" method="post">'
								.'<a href="'.action('IncomeController@show', $financetransaction['id']).'" data-title="'. _lang('View Income Details') .'" class="btn btn-primary btn-sm ajax-modal">'. _lang('View') .'</a>&nbsp;'
								.'<a href="'.action('IncomeController@edit', $financetransaction['id']).'" data-title="'. _lang('Update Income') .'" class="btn btn-warning btn-sm ajax-modal">'._lang('Edit') .'</a>&nbsp;'
								.csrf_field()
								.'<input name="_method" type="hidden" value="DELETE">'
								.'<button class="btn btn-danger btn-sm btn-remove" type="submit">'._lang('Delete') .'</button>'
								.'</form>';
						})
						->setRowId(function ($financetransaction) {
							return "row_".$financetransaction->id;
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
           return view('backend.accounting.income.create');
        }else{
           return view('backend.accounting.income.modal.create');
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
            'trans_date' => 'required',
			'chart_of_account_id' => 'required',
			'amount' => 'required|numeric',
			'reference' => '',
			'note' => '',
			'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('income.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        $attachment = "";
        if($request->hasfile('attachment'))
	    {
		    $file = $request->file('attachment');
		    $attachment = time().$file->getClientOriginalName();
		    $file->move(public_path()."/uploads/transactions/", $attachment);
	    }

        $financetransaction = new FinanceTransaction();
        $financetransaction->trans_date = $request->input('trans_date');
        $financetransaction->chart_of_account_id = $request->input('chart_of_account_id');
		$financetransaction->type = 'income';
		$financetransaction->amount = $request->input('amount');
		$financetransaction->reference = $request->input('reference');
		$financetransaction->note = $request->input('note');
		$financetransaction->attachment = $attachment;

        $financetransaction->save();

        if(! $request->ajax()){
           return redirect()->route('income.create')->with('success', _lang('Saved Sucessfully'));
        }else{
           return response()->json(['result'=>'success','message'=>_lang('Saved Sucessfully'), 'data'=>$financetransaction, 'table' => '#finance_transactions_table']);
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
        $financetransaction = FinanceTransaction::find($id);
        if(! $request->ajax()){
            return view('backend.accounting.income.view',compact('financetransaction','id'));
        }else{
            return view('backend.accounting.income.modal.view',compact('financetransaction','id'));
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
        $financetransaction = FinanceTransaction::find($id);
        if(! $request->ajax()){
            return view('backend.accounting.income.edit',compact('financetransaction','id'));
        }else{
            return view('backend.accounting.income.modal.edit',compact('financetransaction','id'));
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
			'trans_date' => 'required',
			'chart_of_account_id' => 'required',
			'amount' => 'required|numeric',
			'reference' => '',
			'note' => '',
			'attachment' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('income.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        if($request->hasfile('attachment'))
	    {
		    $file = $request->file('attachment');
		    $attachment = time().$file->getClientOriginalName();
		    $file->move(public_path()."/uploads/transactions/", $attachment);
	    }	
		
        $financetransaction = FinanceTransaction::find($id);
		$financetransaction->trans_date = $request->input('trans_date');
		$financetransaction->chart_of_account_id = $request->input('chart_of_account_id');
        $financetransaction->type = 'income';
		$financetransaction->amount = $request->input('amount');
		$financetransaction->reference = $request->input('reference');
		$financetransaction->note = $request->input('note');
		if($request->hasfile('attachment')){
			$financetransaction->attachment = $attachment;
		}
	
        $financetransaction->save();
		
		if(! $request->ajax()){
           return redirect()->route('income.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success', 'message'=>_lang('Updated Sucessfully'), 'data'=>$financetransaction, 'table' => '#finance_transactions_table']);
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
        $financetransaction = FinanceTransaction::find($id);
        $financetransaction->delete();
        return redirect()->route('income.index')->with('success',_lang('Deleted Sucessfully'));
    }
}
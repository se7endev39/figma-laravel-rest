<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoanCollateral;
use Validator;

class LoanCollateralController extends Controller
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
    public function index($loan_id)
    {
        $loancollaterals = LoanCollateral::where('loan_id',$loan_id)
                                         ->orderBy("id","desc")
                                         ->get();                              
        return view('backend.loan_collateral.list',compact('loancollaterals','loan_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $loan_id = $request->get('loan_id');

        if( ! $request->ajax()){
           return view('backend.loan_collateral.create',compact('loan_id'));
        }else{
           return view('backend.loan_collateral.modal.create',compact('loan_id'));
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
            'loan_id' => 'required',
			'name' => 'required',
			'collateral_type' => 'required',
			'serial_number' => '',
			'estimated_price' => 'required|numeric',
			'attachments' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
			'description' => '',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('loan_collaterals.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        $attachments = "";
        if($request->hasfile('attachments'))
	    {
		    $file = $request->file('attachments');
		    $attachments = time().$file->getClientOriginalName();
		    $file->move(public_path()."/uploads/media/", $attachments);
	    }

        $loancollateral = new LoanCollateral();
        $loancollateral->loan_id = $request->input('loan_id');
		$loancollateral->name = $request->input('name');
		$loancollateral->collateral_type = $request->input('collateral_type');
		$loancollateral->serial_number = $request->input('serial_number');
		$loancollateral->estimated_price = $request->input('estimated_price');
		$loancollateral->attachments = $attachments;
		$loancollateral->description = $request->input('description');

        $loancollateral->save();

        if(! $request->ajax()){
           return redirect()->route('loans.show', $loancollateral->loan_id)->with('success', _lang('Saved Sucessfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$loancollateral, 'table' => '#loan_collaterals_table']);
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
        $loancollateral = LoanCollateral::find($id);
        if(! $request->ajax()){
            return view('backend.loan_collateral.view',compact('loancollateral','id'));
        }else{
            return view('backend.loan_collateral.modal.view',compact('loancollateral','id'));
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
        $loancollateral = LoanCollateral::find($id);
        if(! $request->ajax()){
            return view('backend.loan_collateral.edit',compact('loancollateral','id'));
        }else{
            return view('backend.loan_collateral.modal.edit',compact('loancollateral','id'));
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
			//'loan_id' => 'required',
			'name' => 'required',
			'collateral_type' => 'required',
			'serial_number' => '',
			'estimated_price' => 'required|numeric',
			'attachments' => 'nullable|mimes:jpeg,png,jpg,doc,pdf,docx,zip',
			'description' => '',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('loan_collaterals.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        if($request->hasfile('attachments'))
	    {
		    $file = $request->file('attachments');
		    $attachments = time().$file->getClientOriginalName();
		    $file->move(public_path()."/uploads/media/", $attachments);
	    }	
		
        $loancollateral = LoanCollateral::find($id);
		//$loancollateral->loan_id = $request->input('loan_id');
		$loancollateral->name = $request->input('name');
		$loancollateral->collateral_type = $request->input('collateral_type');
		$loancollateral->serial_number = $request->input('serial_number');
		$loancollateral->estimated_price = $request->input('estimated_price');
		if($request->hasfile('attachments')){
			$loancollateral->attachments = $attachments;
		}
		$loancollateral->description = $request->input('description');
	
        $loancollateral->save();
		
		if(! $request->ajax()){
           return redirect()->route('loans.show', $loancollateral->loan_id)->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$loancollateral, 'table' => '#loan_collaterals_table']);
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
        $loancollateral = LoanCollateral::find($id);
        $loancollateral->delete();
        return back()->with('success',_lang('Deleted Sucessfully'));
    }
}
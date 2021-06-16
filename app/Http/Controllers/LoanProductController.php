<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LoanProduct;
use Validator;

class LoanProductController extends Controller
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
        $loanproducts = LoanProduct::all()->sortByDesc("id");
        return view('backend.loan_product.list',compact('loanproducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.loan_product.create');
        }else{
           return view('backend.loan_product.modal.create');
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
            'name' => 'required',
			'description' => '',
			'interest_rate' => 'required|numeric',
			'interest_type' => 'required',
			'term' => 'required|integer',
			'term_period' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('loan_products.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        

        $loanproduct = new LoanProduct();
        $loanproduct->name = $request->input('name');
		$loanproduct->description = $request->input('description');
		$loanproduct->interest_rate = $request->input('interest_rate');
		$loanproduct->interest_type = $request->input('interest_type');
		$loanproduct->term = $request->input('term');
		$loanproduct->term_period = $request->input('term_period');

        $loanproduct->save();

        //Prefix Output
        $loanproduct->interest_type = ucwords(str_replace("_"," ", $loanproduct->interest_type));
        $loanproduct->term_period = ucwords($loanproduct->term_period);

        if(! $request->ajax()){
           return redirect()->route('loan_products.index')->with('success', _lang('Saved Sucessfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$loanproduct, 'table' => '#loan_products_table']);
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
        $loanproduct = LoanProduct::find($id);
        if(! $request->ajax()){
            return view('backend.loan_product.view',compact('loanproduct','id'));
        }else{
            return view('backend.loan_product.modal.view',compact('loanproduct','id'));
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
        $loanproduct = LoanProduct::find($id);
        if(! $request->ajax()){
            return view('backend.loan_product.edit',compact('loanproduct','id'));
        }else{
            return view('backend.loan_product.modal.edit',compact('loanproduct','id'));
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
			'name' => 'required',
			'description' => '',
			'interest_rate' => 'required|numeric',
			'interest_type' => 'required',
			'term' => 'required|integer',
			'term_period' => 'required',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('loan_products.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        			
        $loanproduct = LoanProduct::find($id);
		$loanproduct->name = $request->input('name');
		$loanproduct->description = $request->input('description');
		$loanproduct->interest_rate = $request->input('interest_rate');
		$loanproduct->interest_type = $request->input('interest_type');
		$loanproduct->term = $request->input('term');
		$loanproduct->term_period = $request->input('term_period');
	
        $loanproduct->save();

        //Prefix Output
        $loanproduct->interest_type = ucwords(str_replace("_"," ", $loanproduct->interest_type));
        $loanproduct->term_period = ucwords($loanproduct->term_period);
		
		if(! $request->ajax()){
           return redirect()->route('loan_products.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$loanproduct, 'table' => '#loan_products_table']);
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
        $loanproduct = LoanProduct::find($id);
        $loanproduct->delete();
        return redirect()->route('loan_products.index')->with('success',_lang('Deleted Sucessfully'));
    }
}
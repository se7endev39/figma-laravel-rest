<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomField;
use Validator;

class CustomFieldController extends Controller
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
        $customfields = CustomField::all()->sortByDesc("id");
        return view('backend.custom_field.list',compact('customfields'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
           return view('backend.custom_field.create');
        }else{
           return view('backend.custom_field.modal.create');
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
            'field_name' => 'required',
			'field_type' => 'required',
			'validation_rules' => 'required',
			'status' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('custom_fields.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        

        $customfield = new CustomField();
        $customfield->field_name = $request->input('field_name');
		$customfield->field_type = $request->input('field_type');
		$customfield->default_valus = $request->input('default_valus');
		$customfield->validation_rules = $request->input('validation_rules');
		$customfield->form_type = $request->input('form_type');
		$customfield->section_id = $request->input('section_id');
        $customfield->status = $request->input('status');

        $customfield->save();

        //Prefix Output
        $customfield->field_type = ucwords($customfield->field_type);
        $customfield->validation_rules = ucwords($customfield->validation_rules);
        $customfield->status = $customfield->status == 1 ? _lang('Active') : _lang('In Active');


        if(! $request->ajax()){
           return back()->with('success', _lang('Saved Sucessfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$customfield, 'table' => '#custom_fields_table']);
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
        $customfield = CustomField::find($id);
        if(! $request->ajax()){
            return view('backend.custom_field.view',compact('customfield','id'));
        }else{
            return view('backend.custom_field.modal.view',compact('customfield','id'));
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
        $customfield = CustomField::find($id);
        if(! $request->ajax()){
            return view('backend.custom_field.edit',compact('customfield','id'));
        }else{
            return view('backend.custom_field.modal.edit',compact('customfield','id'));
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
            'field_name' => 'required',
            'field_type' => 'required',
            'validation_rules' => 'required',
            'status' => 'required',
        ]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('custom_fields.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        		
        $customfield = CustomField::find($id);
		$customfield->field_name = $request->input('field_name');
		$customfield->field_type = $request->input('field_type');
		$customfield->default_valus = $request->input('default_valus');
		$customfield->validation_rules = $request->input('validation_rules');
		$customfield->form_type = $request->input('form_type');
		$customfield->section_id = $request->input('section_id');
        $customfield->status = $request->input('status');
	
        $customfield->save();

        //Prefix Output
        $customfield->field_type = ucwords($customfield->field_type);
        $customfield->validation_rules = ucwords($customfield->validation_rules);
        $customfield->status = $customfield->status == 1 ? _lang('Active') : _lang('In Active');
		
		if(! $request->ajax()){
           return back()->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$customfield, 'table' => '#custom_fields_table']);
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
        $customfield = CustomField::find($id);
        $customfield->delete();
        return back()->with('success',_lang('Deleted Sucessfully'));
    }
}
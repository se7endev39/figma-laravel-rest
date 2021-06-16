<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CFSection;
use Validator;

class CFSectionController extends Controller
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
        $cfsections = CFSection::all()->sortByDesc("id");
        return view('backend.custom_field.custom_field_section.list',compact('cfsections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( $request->ajax()){
           return view('backend.custom_field.custom_field_section.modal.create');
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
            'section_name' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('custom_field_sections.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        }
	
        

        $cfsection = new CFSection();
        $cfsection->section_name = $request->input('section_name');

        $cfsection->save();

        if(! $request->ajax()){
           return back()->with('success', _lang('Saved Sucessfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$cfsection, 'table' => '#custom_field_sections_table']);
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
        $cfsection = CFSection::find($id);
        if($request->ajax()){
            return view('backend.custom_field.custom_field_section.modal.view',compact('cfsection','id'));
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
        $cfsection = CFSection::find($id);
        if($request->ajax()){
            return view('backend.custom_field.custom_field_section.modal.edit',compact('cfsection','id'));
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
			'section_name' => 'required',
		]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('custom_field_sections.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $cfsection = CFSection::find($id);
		$cfsection->section_name = $request->input('section_name');
	
        $cfsection->save();
		
		if(! $request->ajax()){
           return back()->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$cfsection, 'table' => '#custom_field_sections_table']);
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
        $cfsection = CFSection::find($id);
        $cfsection->delete();
        return back()->with('success',_lang('Deleted Sucessfully'));
    }
}
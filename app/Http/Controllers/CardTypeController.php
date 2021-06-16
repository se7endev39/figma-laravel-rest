<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CardType;
use Validator;
use Illuminate\Validation\Rule;

class CardTypeController extends Controller
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
        $cardtypes = CardType::all()->sortByDesc("id");
        return view('backend.card_type.list',compact('cardtypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.card_type.create');
		}else{
           return view('backend.card_type.modal.create');
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
			'card_type' => 'required|max:50',
		    'currency_id' => 'required'
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('card_types.create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
	    
		
        $cardtype = new CardType();
	    $cardtype->card_type = $request->input('card_type');
	    $cardtype->currency_id = $request->input('currency_id');
	
        $cardtype->save();

        //Prefix Output
        $cardtype->currency_id = $cardtype->currency->name;
        
		if(! $request->ajax()){
           return redirect()->route('card_types.create')->with('success', _lang('Saved Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$cardtype]);
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
        $cardtype = CardType::find($id);
		if(! $request->ajax()){
		    return view('backend.card_type.view',compact('cardtype','id'));
		}else{
			return view('backend.card_type.modal.view',compact('cardtype','id'));
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
        $cardtype = CardType::find($id);
		if(! $request->ajax()){
		   return view('backend.card_type.edit',compact('cardtype','id'));
		}else{
           return view('backend.card_type.modal.edit',compact('cardtype','id'));
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
			'card_type' => 'required|max:50',
		    'currency_id' => 'required'
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('card_types.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $cardtype = CardType::find($id);
		$cardtype->card_type = $request->input('card_type');
	    $cardtype->currency_id = $request->input('currency_id');
	
        $cardtype->save();

        //Prefix Output
        $cardtype->currency_id = $cardtype->currency->name;
		
		if(! $request->ajax()){
           return redirect()->route('card_types.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$cardtype]);
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
        $cardtype = CardType::find($id);
        $cardtype->delete();
        return redirect()->route('card_types.index')->with('success',_lang('Deleted Sucessfully'));
    }
}

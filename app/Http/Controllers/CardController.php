<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
use Validator;
use Illuminate\Validation\Rule;
use Auth;
use DB;

class CardController extends Controller
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
        $cards = Card::select('cards.*',DB::raw("((SELECT IFNULL(SUM(amount),0) 
                           FROM card_transactions WHERE dr_cr = 'cr' AND status = 1 AND card_id = cards.id) - 
                           (SELECT IFNULL(SUM(amount),0) FROM card_transactions WHERE dr_cr = 'dr' 
                           AND status = 1 AND card_id = cards.id)) as balance"))
                           ->orderBy('id','desc')
                           ->get();
        return view('backend.card.list',compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.card.create');
		}else{
           return view('backend.card.modal.create');
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
			'user_id' => 'required',
			'card_number' => 'required|unique:cards|max:50',
			'card_type_id' => 'required',
			'status' => 'required',
			'expiration_date' => 'required',
			'cvv' => 'required|max:3',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cards.create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
	    
		
        $card = new Card();
	    $card->user_id = $request->input('user_id');
		$card->card_number = $request->input('card_number');
		$card->card_type_id = $request->input('card_type_id');
		$card->status = $request->input('status');
		$card->expiration_date = $request->input('expiration_date');
		$card->cvv = $request->input('cvv');
		$card->note = $request->input('note');
		$card->created_by = Auth::id();
		$card->updated_by = Auth::id();
	
        $card->save();

        //Prefix Outout
        $card->card_type_id = $card->card_type->card_type.' ('.$card->card_type->currency->name.')';
        $card->user_id = "<a href='" . action('UserController@show', $card->user_id) . "' class='ajax-modal' data-title= '". _lang('View User Details') ."'>" . $card->owner->first_name." ".$card->owner->last_name. "</a>";
        $card->status = $card->status == 1 ? _lang('Active') : _lang('Blocked');
        $card->balance = decimalPlace(get_card_balance($card->id));
        
		if(! $request->ajax()){
           return redirect()->route('cards.create')->with('success', _lang('Saved Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$card]);
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
        $card = Card::find($id);
		if(! $request->ajax()){
		    return view('backend.card.view',compact('card','id'));
		}else{
			return view('backend.card.modal.view',compact('card','id'));
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
        $card = Card::find($id);
		if(! $request->ajax()){
		   return view('backend.card.edit',compact('card','id'));
		}else{
           return view('backend.card.modal.edit',compact('card','id'));
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
			'user_id' => 'required',
			'card_number' => [
                'required',
                'max:50',
                Rule::unique('cards')->ignore($id),
            ],
			'card_type_id' => 'required',
			'status' => 'required',
			'expiration_date' => 'required',
			'cvv' => 'required|max:3',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('cards.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $card = Card::find($id);
		$card->user_id = $request->input('user_id');
		$card->card_number = $request->input('card_number');
		$card->card_type_id = $request->input('card_type_id');
		$card->status = $request->input('status');
		$card->expiration_date = $request->input('expiration_date');
		$card->cvv = $request->input('cvv');
		$card->note = $request->input('note');
		$card->updated_by = Auth::id();
	
        $card->save();

         //Prefix Outout
        $card->card_type_id = $card->card_type->card_type.' ('.$card->card_type->currency->name.')';
        $card->user_id = "<a href='" . action('UserController@show', $card->user_id) . "' class='ajax-modal' data-title= '". _lang('View User Details') ."'>" . $card->owner->first_name." ".$card->owner->last_name. "</a>";
        $card->status = $card->status == 1 ? _lang('Active') : _lang('Blocked');
        $card->balance = decimalPlace(get_card_balance( $card->id ));
		
		if(! $request->ajax()){
           return redirect()->route('cards.index')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$card]);
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
    	if(Auth::user()->user_type != 'admin'){
            return back()->with('error',_lang('Permission denied !'));
        }
        $card = Card::find($id);
        $card->delete();
        return redirect()->route('cards.index')->with('success',_lang('Deleted Sucessfully'));
    }
}

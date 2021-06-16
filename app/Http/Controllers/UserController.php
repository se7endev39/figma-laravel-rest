<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\UserInformation;
use App\Document;
use App\Account;
use App\Card;
use App\AccountType;
use App\Transaction;
use App\Deposit;
use App\Withdraw;
use Validator;
use Hash;
use Auth;
use DB;
use Carbon\Carbon;

class UserController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set(get_option('timezone'));
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($as = '')
    {
    	$user_type = Auth::user()->user_type;

		if($as == ''){
			$title = _lang('User List');
			$users = User::where('user_type','user')
			             ->when($user_type, function ($query, $user_type) {
				   		  	 if($user_type == 'staff'){
			                     return $query->where('created_by', Auth::id());
			                 }
		                  })
						 ->orderBy("id","desc")->get();
		}else{
			$title = $as.' '._lang('User List');
			$users = User::where('user_type','user')
						 ->where("account_status",$as)
						 ->when($user_type, function ($query, $user_type) {
				   		  	 if($user_type == 'staff'){
			                     return $query->where('created_by', Auth::id());
			                 }
		                  })
						 ->orderBy("id","desc")->get();
		}			 
        return view('backend.user.list',compact('users','title'));
    }
	
	
    /**
     * Display a listing of users Documents.
     *
     * @return \Illuminate\Http\Response
     */
	
	public function documents()
    {
        $user_type = Auth::user()->user_type;
		$users = User::where('user_type','user')
					 ->when($user_type, function ($query, $user_type) {
				   		  	if($user_type == 'staff'){
			                     return $query->where('users.created_by', Auth::id());
			                }
		              })
					 ->has('documents')->get();
			 
        return view('backend.user.documents',compact('users'));
    }
	
	/**
     * Display single users Documents.
     *
     * @return \Illuminate\Http\Response
     */
	public function view_documents($user_id)
    {
		$documents = Document::where('user_id',$user_id)->get(); 
        $user = User::find($user_id);
		return view('backend.user.view_documents',compact('documents','user'));
    }
	
	
	/**
     * Varify User account.
     *
     * @return \Illuminate\Http\Response
     */
	public function varify($user_id){
		$user = User::find($user_id);
		$user->account_status = "Verified";
		$user->save();
		
		//Send Email/Notification to user
		
		
		//Redirect to back
		return back()->with('varified_success',_lang('Account Verified sucessfully'));
	}
	
	/**
     * Unvarify User account.
     *
     * @return \Illuminate\Http\Response
     */
	public function unvarify($user_id){
		$user = User::find($user_id);
		$user->account_status = "Unverified";
		$user->save();
		
		//Send Email/Notification to user
		
		
		//Redirect to back
		return back()->with('varified_success',_lang('Account Unverified sucessfully'));
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.user.create');
		}else{
           return view('backend.user.modal.create');
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
			'account_type' => 'required|max:15',
			'business_name' => 'required_if:account_type,business|max:191',
			'first_name' => 'required|max:20',
			'last_name' => 'required|max:50',
			'email' => 'required|email|unique:users|max:191',
			'phone' => 'required|unique:users|max:30',
			'password' => 'required|max:20|min:6|confirmed',
			'status' => 'required',
			'account_status' => 'required|max:20',
			'passport' => 'nullable|max:50',
			'city' => 'nullable|max:100',
			'state' => 'nullable|max:100',
			'zip' => 'nullable|max:20',
			'profile_picture' => 'nullable|image|max:5120',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect('admin/users/create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
		DB::beginTransaction();
			
        $user = new User();
	    $user->account_type = $request->input('account_type');
	    $user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->business_name = $request->input('business_name');
		$user->email = $request->input('email');
		if( get_option('email_verification','No') == 'No' ){
			$user->email_verified_at = now();
		}
		$user->phone = $request->input('phone');
		$user->password = Hash::make($request->password);
		$user->user_type = 'user';
		$user->status = $request->input('status');
		$user->account_status = $request->input('account_status');
	    if ($request->hasFile('profile_picture')){
           $image = $request->file('profile_picture');
           $file_name = "profile_".time().'.'.$image->getClientOriginalExtension();
           $image->move(base_path('public/uploads/profile/'),$file_name);
           $user->profile_picture = $file_name;
		}
		$user->created_by = Auth::id();
		$user->updated_by = Auth::id();
		//$user->last_login_at = Carbon::now()->toDateTimeString();
		//$user->last_login_ip = $request->getClientIp();
        $user->save();

        //Create User Information
        $userinformation = new UserInformation();
		$userinformation->user_id = $user->id;
		$userinformation->date_of_birth = $request->input('date_of_birth');
		$userinformation->passport = $request->input('passport');
		$userinformation->country_of_residence = $request->input('country_of_residence');
		$userinformation->country_of_citizenship = $request->input('country_of_citizenship');
		$userinformation->address = $request->input('address');
		$userinformation->city = $request->input('city');
		$userinformation->state = $request->input('state');
		$userinformation->zip = $request->input('zip');
		if( isset($request->others) ){
            $user_information->others = serialize($request->others);
        }
	
        $userinformation->save();

        //Create Auto Account
        $account_types = AccountType::where('auto_create',1)->get();

        foreach($account_types as $account_type){
    	    $account = new Account();
		    $account->account_number = new_account_number();
			$account->user_id = $user->id;
			$account->account_type_id = $account_type->id;
			$account->status = 1;
			$account->opening_balance = 0;
			$account->created_by = Auth::id();
			$account->updated_by = Auth::id();
	        $account->save();

	        update_option( 'next_account_number', ( (int) get_option('next_account_number') + 1) );
        }

        DB::commit();

        //Send Confrimation Email
		
		//Prefix Output
		$user->account_type = ucwords($user->account_type);
		$user->user_type = ucwords($user->user_type);
		$user->status = $user->status == 1 ? status(_lang('Active'),'success') : status(_lang('In-Active'),'danger');
		$user->account_status = $user->account_status == 'Varified' ? status(_lang('Varified'),'success') : status(_lang('Unverified'),'danger');
        
		if(! $request->ajax()){
           return redirect('admin/users/create')->with('success', _lang('Saved Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Sucessfully'),'data'=>$user]);
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
		$user_type = Auth::user()->user_type;
        $user = User::where('id',$id)
                    ->when($user_type, function ($query, $user_type) {
				   		  	 if($user_type == 'staff'){
			                     return $query->where('created_by', Auth::id());
			                 }
		                  })
                    ->first();
		if(! $request->ajax()){
		    return view('backend.user.view',compact('user','id'));
		}else{
			return view('backend.user.modal.view',compact('user','id'));
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
    	$user_type = Auth::user()->user_type;

        $user = User::where('id',$id)
                    ->when($user_type, function ($query, $user_type) {
				   		  	 if($user_type == 'staff'){
			                     return $query->where('created_by', Auth::id());
			                 }
		                  })
                    ->first();

		if(! $request->ajax()){
		   return view('backend.user.edit',compact('user','id'));
		}else{
           return view('backend.user.modal.edit',compact('user','id'));
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
			'account_type' => 'required|max:15',
			'business_name' => 'required_if:account_type,business|max:191',
			'first_name' => 'required|max:50',
			'last_name' => 'required|max:50',
			'email' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
			'phone' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
			'password' => 'nullable|max:20|min:6|confirmed',
			'account_status' => 'required|max:20',
			'status' => 'required',
			'passport' => 'nullable|max:50',
			'city' => 'nullable|max:100',
			'state' => 'nullable|max:100',
			'zip' => 'nullable|max:20',
			'profile_picture' => 'nullable|image|max:5120',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('users.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $user_type = Auth::user()->user_type;

        $user = User::where('id',$id)
                    ->when($user_type, function ($query, $user_type) {
				   		  	 if($user_type == 'staff'){
			                     return $query->where('created_by', Auth::id());
			                 }
		                  })
                    ->first();
		$user->account_type = $request->input('account_type');
		$user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->business_name = $request->input('business_name');
		$user->email = $request->input('email');
		$user->phone = $request->input('phone');
		if($request->password){
            $user->password = Hash::make($request->password);
        }
		$user->user_type = 'user';
		$user->status = $request->input('status');
		$user->account_status = $request->input('account_status');
	    if ($request->hasFile('profile_picture')){
           $image = $request->file('profile_picture');
           $file_name = "profile_".time().'.'.$image->getClientOriginalExtension();
           $image->move(base_path('public/uploads/profile/'),$file_name);
           $user->profile_picture = $file_name;
		}
		$user->updated_by = Auth::id();
        $user->save();

        //Update User Information
        $userinformation = $user->user_information;
		$userinformation->date_of_birth = $request->input('date_of_birth');
		$userinformation->passport = $request->input('passport');
		$userinformation->country_of_residence = $request->input('country_of_residence');
		$userinformation->country_of_citizenship = $request->input('country_of_citizenship');
		$userinformation->address = $request->input('address');
		$userinformation->city = $request->input('city');
		$userinformation->state = $request->input('state');
		$userinformation->zip = $request->input('zip');
		if( isset($request->others) ){
            $user_information->others = serialize($request->others);
        }
	
        $userinformation->save();
		
		//Prefix Output
		$user->account_type = ucwords($user->account_type);
		$user->user_type = ucwords($user->user_type);
		$user->status = $user->status == 1 ? status(_lang('Active'),'success') : status(_lang('In-Active'),'danger');
		$user->account_status = $user->account_status == 'Varified' ? status(_lang('Varified'),'success') : status(_lang('Unverified'),'danger');
		
		if(! $request->ajax()){
           return redirect('admin/users')->with('success', _lang('Updated Sucessfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Sucessfully'),'data'=>$user]);
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
    	
    	DB::beginTransaction();

        $user_type = Auth::user()->user_type;

        $user = User::where('id',$id)
                    ->when($user_type, function ($query, $user_type) {
				   		  	 if($user_type == 'staff'){
			                     return $query->where('created_by', Auth::id());
			                 }
		                  });
        
        
        if($user){
        	$user->delete();
	        Account::where('user_id',$id)->delete();
	        Transaction::where('user_id',$id)->delete();
	        Deposit::where('user_id',$id)->delete();
	        Withdraw::where('user_id',$id)->delete();
	        Card::where('user_id',$id)->delete();
	    }
        
        DB::commit();

        return redirect('admin/users')->with('success',_lang('Removed Sucessfully'));
    }
}

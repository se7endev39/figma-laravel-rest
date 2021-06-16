<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use Validator;
use Hash;

class StaffController extends Controller
{
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('user_type','!=','user')
					 ->orderBy("id","desc")->get();
        return view('backend.staff.list',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		if( ! $request->ajax()){
		   return view('backend.staff.create');
		}else{
           return view('backend.staff.modal.create');
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
			'first_name' => 'required|max:191',
			'last_name' => 'required|max:191',
			'email' => 'required|email|unique:users|max:191',
			'phone' => 'required|unique:users|max:191',
			'password' => 'required|max:20|min:6|confirmed',
			'user_type' => 'required',
			'status' => 'required',
			'profile_picture' => 'nullable|image|max:5120',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect('admin/staffs/create')
							->withErrors($validator)
							->withInput();
			}			
		}
			
        $user= new User();
	    $user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->email = $request->input('email');
		$user->phone = $request->input('phone');
		$user->password = Hash::make($request->password);
		$user->user_type = $request->input('user_type');
		$user->status = $request->input('status');
		$user->email_verified_at = date('Y-m-d H:i:s');
	    if ($request->hasFile('profile_picture')){
           $image = $request->file('profile_picture');
           $file_name = "profile_".time().'.'.$image->getClientOriginalExtension();
           $image->move(base_path('public/uploads/profile/'),$file_name);
           $user->profile_picture = $file_name;
		}
        $user->save();
		
		//Prefix Output
		$user->name = $user->first_name.' '.$user->last_name;
		$user->user_type = ucwords($user->user_type);
		$user->status = $user->status == 1 ? status(_lang('Active'),'success') : status(_lang('In-Active'),'danger');
		
		if(! $request->ajax()){
           return redirect('admin/staffs/create')->with('success', _lang('Saved Sucessfully'));
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
        $user = User::find($id);
		if(! $request->ajax()){
		    return view('backend.staff.view',compact('user','id'));
		}else{
			return view('backend.staff.modal.view',compact('user','id'));
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
        $user = User::find($id);
		if(! $request->ajax()){
		   return view('backend.staff.edit',compact('user','id'));
		}else{
           return view('backend.staff.modal.edit',compact('user','id'));
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
			'first_name' => 'required|max:191',
			'last_name' => 'required|max:191',
			'email' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
			'phone' => [
                'required',
                Rule::unique('users')->ignore($id),
            ],
			'password' => 'nullable|max:20|min:6|confirmed',
			'user_type' => 'required',
			'status' => 'required',
			'profile_picture' => 'nullable|image|max:5120',
		]);
		
		if ($validator->fails()) {
			if($request->ajax()){ 
			    return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('staffs.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}
	
        	
		
        $user = User::find($id);
		$user->first_name = $request->input('first_name');
		$user->last_name = $request->input('last_name');
		$user->email = $request->input('email');
		$user->phone = $request->input('phone');
		if($request->password){
            $user->password = Hash::make($request->password);
        }
		$user->user_type = $request->input('user_type');
		$user->status = $request->input('status');
	    if ($request->hasFile('profile_picture')){
           $image = $request->file('profile_picture');
           $file_name = "profile_".time().'.'.$image->getClientOriginalExtension();
           $image->move(base_path('public/uploads/profile/'),$file_name);
           $user->profile_picture = $file_name;
		}
        $user->save();
		
        //Prefix Output
		$user->name = $user->first_name.' '.$user->last_name;
		$user->user_type = ucwords($user->user_type);
		$user->status = $user->status == 1 ? status(_lang('Active'),'success') : status(_lang('In-Active'),'danger');
		
		
		if(! $request->ajax()){
           return redirect('admin/staffs')->with('success', _lang('Updated Sucessfully'));
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
        $user = User::find($id);
        $user->delete();
        return redirect('admin/staffs')->with('success',_lang('Removed Sucessfully'));
    }
}

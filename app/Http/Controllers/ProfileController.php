<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use Hash;
use Auth;

class ProfileController extends Controller
{

    public function edit()
    {
        $profile = Auth::user();
        return view('backend.profile.profile_edit',compact('profile'));
    }


    public function update(Request $request)
    {
        if(Auth::user()->user_type == 'user'){
            $this->validate($request, [
                'first_name' => 'required|max:20',
    			'last_name' => 'required|max:50',
                'phone' => [
                    'required',
                    Rule::unique('users')->ignore(Auth::id()),
                ],
                'email' => [
                    'required',
                    Rule::unique('users')->ignore(Auth::id()),
                ],
                'date_of_birth' => 'required',
                'passport' => 'required|max:50',
                'country_of_residence' => 'required',
                'country_of_citizenship' => 'required',
                'address' => 'required',
                'city' => 'required|max:100',
                'state' => 'required|max:100',
                'zip' => 'required|max:20',
                'profile_picture' => 'nullable|image|max:5120',
            ]);
        }else{
              $this->validate($request, [
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:50',
                'phone' => [
                    'required',
                    Rule::unique('users')->ignore(Auth::id()),
                ],
                'email' => [
                    'required',
                    Rule::unique('users')->ignore(Auth::id()),
                ],
                'profile_picture' => 'nullable|image|max:5120',
            ]);
        }

        $profile = Auth::user();
        $profile->first_name = $request->first_name;
		$profile->last_name = $request->last_name;
        $profile->email = $request->email;
		$profile->phone = $request->phone;
		$profile->language = $request->language;
		
		if ($request->hasFile('profile_picture')){
            $image = $request->file('profile_picture');
            $file_name = "profile_".time().'.'.$image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/profile/'),$file_name);
            $profile->profile_picture = $file_name;
        }
        $profile->save();

        if(Auth::user()->user_type == 'user'){
            $userinformation = $profile->user_information;
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
        }
		
		if($profile->language != null){
			session(['language' => $profile->language]);
		}

        return redirect('profile/edit')->with('success', _lang('Profile updated sucessfully'));
    }

    /**
     * Show the form for change_password the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_password()
    {
        return view('backend.profile.change_password');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $this->validate($request, [
            'oldpassword' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find(Auth::User()->id);
        if(Hash::check($request->oldpassword, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
        }else{
            return back()->with('error', _lang('Old Password did not match !'));
        }
        return back()->with('success', _lang('Password has been changed'));
    }

    /**
     * Show referral link.
     *
     * @return \Illuminate\Http\Response
     */
    public function referral_link()
    {
        //$user = User::whereRaw("md5(id) = ?",['eccbc87e4b5ce2fe28308fd9f2a7baf3'])->first();
        return view('backend.profile.referral_link');
    }

}

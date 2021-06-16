<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use App\UserInformation;
use App\Account;
use App\AccountType;
use Validator;
use DB;
use Hash;

class AuthController extends Controller
{
	
	public $successStatus = 200;
	public $errorStatus = 401;
	
    /**
	* Create a new AuthController instance.
	*
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login', 'register']]);
	}
	
	/**
	* Get a JWT via given credentials.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function register(Request $request)
	{
		console.log("register");
		$validator = Validator::make($request->all(), [
			'account_type'           => 'required|string|max:15',
            'business_name'          => 'required_if:account_type,business|max:191',
            'first_name'             => 'required|string|max:191',
            'last_name'              => 'required|string|max:191',
            'email'                  => 'required|string|email|max:191|unique:users',
            'phone'                  => 'required|string|max:30|unique:users',
            'password'               => 'required|string|min:6|confirmed',
            'date_of_birth'          => 'required',
            'passport'               => 'required|max:50',
            'country_of_residence'   => 'required',
            'country_of_citizenship' => 'required',
            'address'                => 'required',
            'city'                   => 'required|max:100',
            'state'                  => 'required|max:100',
            'zip'                    => 'required|max:20',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
		
		
		DB::beginTransaction();

        $user = new User();
        $user->account_type = $request->account_type;
        $user->business_name = isset($request->business_name) ? $request->business_name : NULL;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
		
		if( get_option('email_verification','No') == 'No' ){
			$user->email_verified_at = now();
		}
		
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->user_type = 'user';
        $user->status = 1;
        $user->account_status = isset($request->account_status) ? $request->account_status : 'Unverified';
		
		if($request->ref != ''){
			$reference = User::whereRaw("md5(id) = ?",[$request->ref])->first();
			if($reference){
				$user->refer_user_id = $reference->id;
			}
		}
        $user->save();

        //Create User Information
        $user_information = new UserInformation();
        $user_information->user_id = $user->id;
        $user_information->date_of_birth = $request->date_of_birth;
        $user_information->passport = $request->passport;
        $user_information->country_of_residence = $request->country_of_residence;
        $user_information->country_of_citizenship = $request->country_of_citizenship;
        $user_information->address = $request->address;
        $user_information->city = $request->city;
        $user_information->state = $request->state;
        $user_information->zip = $request->zip;

        if( isset($request->others) ){
            $user_information->others = serialize($request->others);
        }

        $user_information->save();

        //Create Auto Account
        $account_types = AccountType::where('auto_create',1)->get();

        foreach($account_types as $account_type){
            $account = new Account();
            $account->account_number = new_account_number();
            $account->user_id = $user->id;
            $account->account_type_id = $account_type->id;
            $account->status = 1;
            $account->opening_balance = 0;
            $account->created_by = $user->id;
            $account->updated_by = $user->id;
            $account->save();

            update_option( 'next_account_number', ( (int) get_option('next_account_number') + 1) );
        }

        DB::commit();
		
		$data['result']  = true;
		
		if( get_option('email_verification','No') == 'No' ){
			$data['message'] = _lang('Registration Successfully');
		}else{
			$data['message'] = _lang('A Verification link send to your email. Please check your email.');
		}
        $data['data'] = $user;
		
		return response()->json($data, $this->successStatus);
	}
	
	/**
	* Get a JWT via given credentials.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
		
		$credentials = request(['email', 'password']);
		if (! $token = auth('api')->attempt($credentials)) {
			return response()->json(['result'=> false, 'message' => 'Username or Password is incorrect!']);
		}
		
		$user = auth('api')->user();
		
		if( $user->status != 1 ){
			return response()->json(
				array(
					'result'  => false,
					'message' => _lang('Your account is not active. Please contact with your support !'), 	
				));
		}
		
		if( $user->user_type != 'user' ){
			return response()->json(
				array(
					'result'  => false,
					'message' => _lang('Sorry only user account is allowed to login this app !'), 	
				));
		}
		
		if( $user->email_verified_at == null ){
			return response()->json(
				array(
					'result'  => false,
					'message' => _lang('Please verify your email address !'), 	
				));
		}
			
		return $this->respondWithToken($token);
	}
	
	/**
	* Get the authenticated User.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function profile()
	{
		$user = auth('api')->user();
		$other_information = $user->user_information;
		return response()->json(
			array(
				'user' 			    => $user, 
				'other_information' => $other_information
			));
	}
	
	/**
	* Update Profile.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function update_profile(Request $request)
    {	
		$validator = Validator::make($request->all(), [
			'first_name' => 'required|max:20',
			'last_name' => 'required|max:50',
			'phone' => [
				'required',
				Rule::unique('users')->ignore(auth('api')->user()->id),
			],
			'email' => [
				'required',
				Rule::unique('users')->ignore(auth('api')->user()->id),
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
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
		
		DB::beginTransaction();
		
        $profile = auth('api')->user();
        $profile->first_name = $request->input('first_name');
		$profile->last_name = $request->input('last_name');
		$profile->business_name = isset($request->business_name) ? $request->business_name : NULL;
        $profile->email = $request->email;
		$profile->phone = $request->input('phone');
		
		if ($request->hasFile('profile_picture')){
            $image = $request->file('profile_picture');
            $file_name = "profile_".time().'.'.$image->getClientOriginalExtension();
            $image->move(base_path('public/uploads/profile/'),$file_name);
            $profile->profile_picture = $file_name;
        }
        $profile->save();

        
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
        
		DB::commit();
		
		$data['result']  = true;
		$data['message'] = _lang('Profile updated sucessfully');
        $data['data']	 = $profile;
		
		return response()->json($data, $this->successStatus);
    }
	
	/** Update Profile Picture **/
	public function update_profile_picture(Request $request){
		
		$validator = Validator::make($request->all(), [
			'profile_picture' => 'required|image|max:2048',
		]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}
		
		$profile = auth('api')->user();
		
		$image = $request->file('profile_picture');
		$file_name = "profile_".time().'.'.$image->getClientOriginalExtension();
		$image->move(base_path('public/uploads/profile/'),$file_name);
		$profile->profile_picture = $file_name;

        $profile->save();
		
		$data['result']  = true;
		$data['message'] = _lang('Profile picture updated sucessfully');
        $data['data']	 = $profile;
		
		return response()->json($data, $this->successStatus);
	}
	
	/** Update Password **/
	public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' 	   => 'required|string|min:6|confirmed',
        ]);
		
		if ($validator->fails()) {
			return response()->json(['result' => false, 'message' => $validator->messages()]);		
		}

        $user = auth('api')->user();
		
        if(Hash::check($request->old_password, $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
        }else{
			return response()->json(['result' => false, 'message' => _lang('Old Password did not match !')]);		
        }
		
		return response()->json(['result' => true, 'message' => _lang('Password updated sucessfully')]);
    }

	
	/**
	* Log the user out (Invalidate the token).
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function logout()
	{
		auth('api')->logout();
		return response()->json(['message' => 'Successfully logged out']);
	}
	
	/**
	* Refresh a token.
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	public function refresh()
	{
		return $this->respondWithToken(auth('api')->refresh());
	}
	
	/**
	* Get the token array structure.
	*
	* @param string $token
	*
	* @return \Illuminate\Http\JsonResponse
	*/
	protected function respondWithToken($token)
	{
		return response()->json([
		    'result'	    => true,
			'access_token'  => $token,
			'user'          => $this->guard()->user(),
			'token_type'    => 'bearer',
			'expires_in'    => auth('api')->factory()->getTTL() * 60
		]);
	}
	
	public function guard(){
		return \Auth::Guard('api');
	}
}

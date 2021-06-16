<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserInformation;
use App\Account;
use App\AccountType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Utilities\Overrider;
use DB;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		Overrider::load("Settings");
		date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        if(get_option('allow_singup','yes') != 'yes'){
            return redirect('login');
        }else{
            return view('auth.register');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $custom_fields = \App\CustomField::where('form_type','signup')
                                         ->orderBy('section_id')
                                         ->get();
        $rules = [
            'account_type' => 'required|string|max:15',
            'business_name' => 'required_if:account_type,business|max:191',
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'phone' => 'required|string|max:30|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'date_of_birth' => 'required',
            'prince_plan_type' => 'required|string|max:15',
            'passport' => 'required|max:50',
            'country_of_residence' => 'required',
            'country_of_citizenship' => 'required',
            'address' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'zip' => 'required|max:20',    
        ];

        $message = [];

        foreach($custom_fields as $field){
            if( $field->validation_rules == 'yes' ){
                $name = str_replace(' ','_',$field->field_name);
                $rules["custom_field.$name"] = 'required';
                $message["custom_field.$name.required"] = $field->field_name.' is required';
            }
        }

        return Validator::make($data, $rules, $message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        try{
            DB::beginTransaction();
            $user = new User();
            $user->account_type = $data['account_type'];
            console.log($data['prince_plan_type']);
            $user->prince_plan_type = $data['prince_plan_type'];

            $user->business_name = isset($data['business_name']) ? $data['business_name'] : NULL;
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            if( get_option('email_verification','No') == 'No' ){
                $user->email_verified_at = now();
            }
            $user->phone = $data['phone'];
            $user->password = Hash::make($data['password']);
            $user->user_type = 'user';
            $user->status = 1;
            $user->account_status = 'Unverified';
            $user->last_login_at = Carbon::now()->toDateTimeString();
            $user->last_login_ip = request()->getClientIp();
            if($data['ref'] != ''){
                $reference = User::whereRaw("md5(id) = ?",[$data['ref']])->first();
                if($reference){
                    $user->refer_user_id = $reference->id;
                }
            }
            $user->save();

            //Create User Information
            $user_information = new UserInformation();
            $user_information->user_id = $user->id;
            $user_information->date_of_birth = $data['date_of_birth'];
            $user_information->passport = $data['passport'];
            $user_information->country_of_residence = $data['country_of_residence'];
            $user_information->country_of_citizenship = $data['country_of_citizenship'];
            $user_information->address = $data['address'];
            $user_information->city = $data['city'];
            $user_information->state = $data['state'];
            $user_information->zip = $data['zip'];

            if( isset($data['custom_field']) ){
                $user_information->others = serialize($data['custom_field']);
            }

            $user_information->save();

            //Create Auto Account
            $account_types = AccountType::where('auto_create',1)->get();

            //@TODO inserte prince_plan_type part and doing 
            
            ///Standard 0$/month
            ///Free BTC Account
            ///Free Virtual Bank Of West Africa MasterCard
            ///Spend in over 150 currencies at the interbank exchange rate
            ///Unlimited exchange in 24 fiat currencies - no monthly limit
            
            ///Premium 6.0$/month
            ///MORE
            ///No ATM withdrawal fees up to 200 USD a month

            ///Gold 25.0$/month
            ///MORE
            ///Priority 24/7 customer support
            ///Instant access to 5 cryptocurrencies
            ///Exclusive concierge service
            ///Disposable virtual cards or additional cards for your staff at reduced fee

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

            return $user;
        }
        catch(\Exception $e){
            // console.log($e);
            return $e;
            //Nothing
        }
    }
	
}

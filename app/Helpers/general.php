<?php

if ( ! function_exists('_lang')){
	function _lang( $string = '' ){

		//Get Target language
		$target_lang = get_language();
		
		if($target_lang == ""){
			$target_lang = "language";
		}
		
		if(file_exists(resource_path() . "/language/$target_lang.php")){
			include(resource_path() . "/language/$target_lang.php"); 
		}else{
			include(resource_path() . "/language/language.php"); 
		}
		
		if (array_key_exists($string,$language)){
			return $language[$string];
		}else{
			return $string;
		}
	}
}


if ( ! function_exists('startsWith')){
	function startsWith($haystack, $needle)
	{
		 $length = strlen($needle);
		 return (substr($haystack, 0, $length) === $needle);
	}
}

if ( ! function_exists('is_image')){
	function is_image($file_path){
		$imageExtensions = ['jpg', 'jpeg', 'gif', 'png'];

		$explodeImage = explode('.', $file_path);
		$extension = end($explodeImage);

		if(in_array($extension, $imageExtensions)){
		    return true;
		}else{
		   return false;
		}
	}
}


if ( ! function_exists('create_option')){
	function create_option( $table, $value, $display, $selected = "", $where = NULL ){
		$options = "";
		$condition = "";
		if($where != NULL){
			$condition .= "WHERE ";
			foreach( $where as $key => $v ){
				$condition.=$key."'".$v."' ";
			}
		}

		$query = DB::select("SELECT $value, $display FROM $table $condition");
		foreach($query as $d){
			if( $selected!="" && $selected == $d->$value ){   
				$options.="<option value='".$d->$value."' selected='true'>".ucwords($d->$display)."</option>";
			}else{
				$options.="<option value='".$d->$value."'>".ucwords($d->$display)."</option>";
			} 
		}
		
		echo $options;
	}
}

if ( ! function_exists('get_table')){
	function get_table( $table, $where = NULL ) 
	{
		$condition = "";
		if($where != NULL){
			$condition .= "WHERE ";
			foreach( $where as $key => $v ){
				$condition.=$key."'".$v."' ";
			}
		}
		$query = DB::select("SELECT * FROM $table $condition");
		return $query;
	}
}

if ( ! function_exists('update_option')){
	function update_option( $name, $value ) 
	{
		date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
		
	    $data = array();
		$data['value'] = $value; 
		$data['updated_at'] = \Carbon\Carbon::now();
		if(\App\Setting::where('name', $name)->exists()){				
			\App\Setting::where('name', $name)->update($data);			
		}else{
			$data['name'] = $name; 
			$data['created_at'] = \Carbon\Carbon::now();
			\App\Setting::insert($data); 
		}
	}
}


if ( ! function_exists('user_count')){
	function user_count( $user_type ) 
	{
		$count = \App\User::where("user_type",$user_type)
						->selectRaw("COUNT(id) as total")
						->first()->total;
	    return $count;
	}
}

if ( ! function_exists('transfer_request_count')){
	function transfer_request_count( $status = 'pending' ) 
	{
		$count = \App\Transaction::where("status", $status)
								 ->where('dr_cr','dr')
								 ->whereRaw("(type ='transfer' OR type ='wire_transfer' OR type = 'card_transfer' OR type = 'payment')")
								 ->count();
	    return $count;
	}
}

if ( ! function_exists('deposit_request_count')){
	function deposit_request_count( $status = 'pending' ) 
	{
		$count = \App\WireDepositRequest::where("status", $status)->count();
	    return $count;
	}
}

if ( ! function_exists('referral_commission_count')){
	function referral_commission_count( ) 
	{
		$count =  \App\ReferralCommission::where('user_id',Auth::id())
                                          ->where('status',1)
                                          ->selectRaw('count(id) as c')
                                          ->groupBy('currency_id')
                                          ->get();
	    return $count->count();
	}
}

if ( ! function_exists('status')){
	function status($label, $badge)
	{
		return "<span class='badge badge-$badge'>$label</span>";
	}
}


if ( ! function_exists('get_logo')){
	function get_logo() 
	{
		$logo = get_option("logo");
		if($logo ==""){
			return asset("images/company-logo.png");
		}
		//$v = filemtime(public_path("uploads/$logo"));
		return asset("uploads/$logo"); 
	}
}

if ( ! function_exists('profile_picture')){
	function profile_picture( $profile_picture = '' ) 
	{
		if( $profile_picture == '' ){
			$profile_picture = Auth::user()->profile_picture;
		}
		return $profile_picture != '' ? asset('uploads/profile/'.$profile_picture) : asset('images/avatar.png'); 
	}
}

if ( ! function_exists('sql_escape')){
	function sql_escape( $unsafe_str ) 
	{
		if (get_magic_quotes_gpc())
		{
			$unsafe_str = stripslashes($unsafe_str);
		}
		return $escaped_str = str_replace("'", "", $unsafe_str);
	}
}

if ( ! function_exists('get_option')){
	function get_option( $name, $optional = "" ) 
	{
		$setting = DB::table('settings')->where('name', $name)->get();
	    if ( ! $setting->isEmpty() ) {
		   return $setting[0]->value;
		}
		return $optional;

	}
}


if ( ! function_exists('timezone_list'))
{

 function timezone_list() {
  $zones_array = array();
  $timestamp = time();
  foreach(timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
    $zones_array[$key]['ZONE'] = $zone;
    $zones_array[$key]['GMT'] = 'UTC/GMT ' . date('P', $timestamp);
  }
  return $zones_array;
}

}

if ( ! function_exists('create_timezone_option'))
{

 function create_timezone_option( $old = "" ) {
  $option = "";
  $timestamp = time();
  foreach(timezone_identifiers_list() as $key => $zone) {
    date_default_timezone_set($zone);
	$selected = $old == $zone ? "selected" : "";
	$option .= '<option value="'. $zone .'"'.$selected.'>'. 'GMT ' . date('P', $timestamp) .' '.$zone.'</option>';
  }
  echo $option;
}

}


if ( ! function_exists( 'get_country_list' ))
{
    function get_country_list( $old_data = '' ) {
		if( $old_data == '' ){
			echo file_get_contents( app_path() . '/Helpers/country.txt' );
		}else{
			$pattern = '<option value="'.$old_data.'">';
			$replace = '<option value="'.$old_data.'" selected="selected">';
			$country_list = file_get_contents( app_path() . '/Helpers/country.txt' );
			$country_list = str_replace($pattern, $replace, $country_list);
			echo $country_list;
		}
    }	
}

if ( ! function_exists('decimalPlace'))
{

 function decimalPlace( $number ){
    return number_format( (float) $number, 2 );
 }

}


if( !function_exists('load_language') ){
	function load_language($active = ''){
		$path = resource_path() . "/language";
		$files = scandir($path);
		$options="";
		
		foreach($files as $file){
		    $name = pathinfo( $file, PATHINFO_FILENAME);
			if($name == "." || $name == "" || $name == "language"){
				continue;
			}
			
			$selected = "";
			if($active == $name){
				$selected = "selected";
			}else{
				$selected = "";
			}
			
			$options .= "<option value='$name' $selected>".ucwords($name)."</option>";
		        
		}
		echo $options;
	}
}

if( !function_exists('get_language_list') ){
	function get_language_list(){
		$path = resource_path() . "/language";
		$files = scandir($path);
		$array = array();
		
		foreach($files as $file){
		    $name = pathinfo($file, PATHINFO_FILENAME);
			if($name == "." || $name == "" || $name == "language"){
				continue;
			}
	
			$array[] = $name;
		        
		}
		return $array;
	}
}

if ( ! function_exists('new_account_number()')){
	function new_account_number() 
	{
		$prefix = get_option('account_number_prefix');
		$account_number = get_option('next_account_number');
		if ( $account_number == '' ){
			$account_number = get_option('next_account_number',date('Y').'1001');
			update_option( 'next_account_number', $account_number ); 
		}
	    return $prefix.$account_number;
	}
}


if ( ! function_exists('get_account_balance'))
{

	function get_account_balance( $account_id, $user_id = '' ){
	   if($user_id == ''){
		  $user_id = Auth::user()->id;
	   }
	 
	   $result = DB::select("SELECT ((SELECT IFNULL(SUM(amount),0) FROM transactions WHERE dr_cr = 'cr' 
	   AND user_id = $user_id AND account_id = $account_id AND status='complete') - (SELECT IFNULL(SUM(amount),0) FROM transactions 
	   WHERE dr_cr = 'dr' AND user_id = $user_id AND account_id = $account_id AND status != 'reject')) as balance");
	   return $result[0]->balance;

	}

}

if ( ! function_exists('get_card_balance'))
{

	function get_card_balance( $card_id ){
		
	   $result = DB::select("SELECT ((SELECT IFNULL(SUM(amount),0) FROM card_transactions WHERE dr_cr = 'cr' 
	   AND card_id = $card_id AND status = 1) - (SELECT IFNULL(SUM(amount),0) FROM card_transactions 
	   WHERE dr_cr = 'dr' AND card_id = $card_id AND status = 1)) as balance");
	   return $result[0]->balance;

	}

}


if ( ! function_exists('get_unread_inbox_messages')){
	function get_unread_inbox_messages(){
		$id = Auth::id();
		$messages = \App\Conversation::join('messages','messages.conversation_id','conversations.id')
				                     ->join('users','users.id','messages.user_id')
						             ->whereRaw('(conversations.sender_id = ? OR conversations.receiver_id = ?)', [$id, $id])
						             ->where('messages.user_id','!=',$id)
						             ->where('messages.is_seen',0)
						             ->select('messages.*')
						             ->orderBy('messages.id','desc')
						             ->get();
	    return $messages;
	}
}


if ( ! function_exists('generate_fee'))
{
    function generate_fee( $amount, $fee, $fee_type ){

		if( $fee_type == 'percent' ){
            return ($fee / 100) * $amount;
		}else if( $fee_type == 'fixed' ){
			return $fee;
		}

    }
}

if ( ! function_exists('generate_gift_card'))
{
	function generate_gift_card( $length = '16' ){
		$code = substr( str_shuffle( str_repeat( '01F23LP45FSMQ678QZ9', $length ) ), 0, $length);
		$code = implode("-", str_split($code, 4));
		
		$db = DB::select("SELECT code FROM gift_cards WHERE code = '$code'");

		if( $db ){
		   generate_gift_card();
		}
		
		return $code;
	}
}

if ( ! function_exists('send_message'))
{
	function send_message( $receiver_id, $subject, $body, $object = null ){
		//Repalce Message Paremeter
		if($object != null){
            foreach ($object as $key => $value) {
            	$src = '{'.$key.'}';
			    $body = str_replace($src, $value, $body);
			}
		}
		
		$admin = \App\User::where('user_type','admin')->first();

		//Create Conversation
        $conversation              = new \App\Conversation();
        $conversation->subject     = $subject;
        $conversation->sender_id   = $admin->id;
        $conversation->receiver_id = $receiver_id;
        $conversation->status      = 1;
        $conversation->save();

        //Create Message
        $message                  = new \App\Message();
        $message->message         = $body;
        $message->user_id         = $conversation->sender_id;
        $message->conversation_id = $conversation->id;
        $message->save();
		
		//Send Email Notifications
		\App\Utilities\Overrider::load("Settings");
		$user = \App\User::find($receiver_id);
		
		try{
			$user->notify(new \App\Notifications\MessageNotification($message));
		}catch(\Exception $e){
			//Nothing
		}
		
	}
}

if ( ! function_exists('get_next_id')){
	function get_next_id($table) 
	{
		$statement = DB::select("show table status like '$table'");
		return $statement[0]->Auto_increment;
	}
}


/** Currency Functions **/

if ( ! function_exists( 'global_currency_list' ))
{
	function global_currency_list( $old_data = '', $serialize = false ) {	
		$currency_list = file_get_contents( app_path().'/Helpers/currency.txt' );
		
		if( $old_data == "" ){
			echo $currency_list;
		}else{
			if($serialize == true){
				$old_data = unserialize($old_data);
				for($i=0; $i<count($old_data); $i++){
					$pattern = '<option value="'.$old_data[$i].'">';
					$replace = '<option value="'.$old_data[$i].'" selected="selected">';
				    $currency_list = str_replace($pattern,$replace,$currency_list);
				}
				echo $currency_list;
			}else{
				$pattern = '<option value="'.$old_data.'">';
				$replace = '<option value="'.$old_data.'" selected="selected">';
				$currency_list = str_replace($pattern,$replace,$currency_list);
				echo $currency_list;
			}
		}
	}	
}

if ( ! function_exists('get_base_currency')){
	function get_base_currency() 
	{
		$currency = \App\Currency::where("base_currency",1)->first();
		if(! $currency){
			$currency = \App\Currency::all()->first();
		}
	    return $currency->name;
	}
}

if ( ! function_exists('get_currency_list')){
	function get_currency_list() 
	{
		$currency_list = \App\Currency::where("status",1)
									  ->orderBy("base_currency","DESC")
									  ->get();
	    return $currency_list;
	}
}

if ( ! function_exists( 'get_currency_symbol' ))
{
	function get_currency_symbol( $currency_code ) {
		include(app_path().'/Helpers/currency_symbol.php');
        
		if (array_key_exists($currency_code, $currency_symbols)){
			//return $currency_symbols[$currency_code];
			return html_entity_decode( $currency_symbols[$currency_code], ENT_QUOTES, 'UTF-8');
		}
		return $currency_code;
		
	}
}

if ( ! function_exists('update_currency_exchange_rate')){
	function update_currency_exchange_rate( $reload = false )
	{
		if(get_option('currency_converter','manual') == 'manual'){
            return;
		}
		date_default_timezone_set(get_option('timezone','Asia/Dhaka'));

		$start  = new \Carbon\Carbon( get_option('currency_update_time',date("Y-m-d H:i:s", strtotime('-24 hours', time())) ) );
		$end    = \Carbon\Carbon::now();
  
		$last_run = $start->diffInHours($end);

		if( $last_run >= 12 || $reload == true ){
			// set API Endpoint and API key 
			$endpoint = 'latest';
			$access_key = get_option('fixer_api_key');

			// Initialize CURL:
			$ch = curl_init('http://data.fixer.io/api/'.$endpoint.'?access_key='.$access_key.'');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$exchangeRates = json_decode($json, true);

			if($exchangeRates['success'] == false){
				return false;
			}

			$base_currency =  $exchangeRates['base'];
			
			$currency_rates = array();
			
			foreach($exchangeRates['rates'] as $currency => $rate){
				$currency_rates[$currency] = array(
										"currency"   => $currency, 
										"rate"       => $rate,
										"created_at" => date('Y-m-d H:i:s'),
										"updated_at" => date('Y-m-d H:i:s')
									);
			}

            $currency_list = \App\Currency::all();

			DB::beginTransaction();

			foreach($currency_list as $currency){
				$c = \App\Currency::find($currency->id);
				if(isset($currency_rates["{$currency->name}"])){
					$c->exchange_rate = $currency_rates["{$currency->name}"]['rate'];
				    $c->save();
				}	
			}
			
			//Store Last Update time
			update_option("currency_update_time", \Carbon\Carbon::now());
			
			DB::commit();
		}
	}
}

if ( ! function_exists('convert_currency'))
{
    function convert_currency($from_currency, $to_currency, $amount){
		$currency1 = \App\Currency::where('name',$from_currency)->first()->exchange_rate;
		$currency2 = \App\Currency::where('name',$to_currency)->first()->exchange_rate;

		$converted_output = ($amount/$currency1) * $currency2;
        return $converted_output;
    }
}

if ( ! function_exists('convert_currency_2'))
{
    function convert_currency_2($currency1_rate, $currency2_rate, $amount){
		$currency1 = $currency1_rate;
		$currency2 = $currency2_rate;

		$converted_output = ($amount/$currency1) * $currency2;
        return $converted_output;
    }
}

if ( ! function_exists('account_currency'))
{
    function account_currency( $account_id ){
		$account = \App\Account::find($account_id);

        return $account->account_type->currency->name;
    }
}

if ( ! function_exists('currency_by_account_number'))
{
    function currency_by_account_number( $account_number ){
		$account = \App\Account::where('account_number',$account_number)->first();
        return $account->account_type->currency->name;
    }
}


if ( ! function_exists('card_currency'))
{
    function card_currency( $card_id ){
		$card = \App\Card::find($card_id);

        return $card->card_type->currency->name;
    }
}

if ( ! function_exists('get_language'))
{
    function get_language(){
		$language = session('language');
		
		if($language == ''){	
			$language = get_option('language');
			session(['language' => $language]);
		}

        return $language;
    }
}


if ( ! function_exists('generate_custom_fields'))
{
    function generate_custom_fields(){
    	$sections = array();
		$custom_fields = \App\CustomField::where('form_type','signup')
		                                 ->where('status',1)
					                     ->orderBy('section_id')
										 ->get();
		foreach($custom_fields as $field){
			if($field->section_id == ''){
               if(! isset($sections[0])){
					$sections[0] = array();
               }
               array_push($sections[0], $field);
			}else{
			   if(! isset($sections[$field->section_id])){
					$sections[$field->section_id] = array();
               }
               array_push($sections[$field->section_id], $field);
			}
		}								 
        
        return $sections;     
    }
}
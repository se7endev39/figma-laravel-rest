<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WireDepositRequest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wire_deposit_requests';
    

    public function account(){
    	return $this->belongsTo('App\Account','credit_account')->withDefault();
    }
	
	public function user(){
    	return $this->belongsTo('App\User','user_id')->withDefault();
    }

}
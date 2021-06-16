<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdraw';
	
	public function account(){
		return $this->belongsTo('App\Account','account_id')->withDefault();
	}
	
	public function user(){
		return $this->belongsTo('App\User','user_id')->withDefault();
	}

	public function transaction(){
		return $this->hasOne('App\Transaction','ref_id')->withDefault();
	}
}
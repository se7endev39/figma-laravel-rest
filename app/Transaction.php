<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    public function created_user(){
		return $this->belongsTo('App\User','created_by')->withDefault();
	}

	public function updated_user(){
		return $this->belongsTo('App\User','updated_by')->withDefault();
	}

	public function user(){
		return $this->belongsTo('App\User','user_id')->withDefault();
	}

	public function account(){
		return $this->belongsTo('App\Account','account_id')->withDefault();
	}

	public function wire_transfer(){
		return $this->hasOne('App\WireTransfer','transaction_id')->withDefault();
	}

	public function credit(){
		return $this->hasOne('App\Transaction','parent_id')->where('type','transfer')->withDefault();
	}

	public function card_transfer(){
		return $this->hasOne('App\CardTransaction','transaction_id')->withDefault();
	}
}
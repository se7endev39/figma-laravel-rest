<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    public function created_user(){
    	return $this->belongsTo('App\User','created_by')->withDefault();
    }

    public function updated_user(){
    	return $this->belongsTo('App\User','updated_by')->withDefault();
    }

    public function account_type(){
    	return $this->belongsTo('App\AccountType','account_type_id')->withDefault();
    }

    public function owner(){
    	return $this->belongsTo('App\User','user_id')->withDefault();
    }

}
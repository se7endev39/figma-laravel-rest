<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'account_types';

    public function currency(){
    	return $this->belongsTo('App\Currency','currency_id')->withDefault();
    }
}
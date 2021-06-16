<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralCommission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'referral_commissions';
	
	public function currency(){
		return $this->belongsTo('App\Currency','currency_id')->withDefault();
	}

}
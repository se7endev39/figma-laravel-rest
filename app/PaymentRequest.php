<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_requests';

    public function account(){
    	return $this->belongsTo('App\Account','account_id')->withDefault();
    }

	public function paid(){
    	return $this->belongsTo('App\User','paid_by')->withDefault();
    }

    public function sender(){
        return $this->belongsTo('App\User','created_by')->withDefault();
    }
	
}
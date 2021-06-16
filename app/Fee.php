<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_fees';
	
	public function transactions(){
		return $this->hasMany('App\Transaction','custom_fee_id');
	}
}
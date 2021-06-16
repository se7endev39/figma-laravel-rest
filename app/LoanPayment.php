<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loan_payments';

    public function loan(){
    	return $this->belongsTo('App\Loan','loan_id')->withDefault();
    }
}
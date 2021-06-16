<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loan_repayments';

    public function loan(){
    	return $this->belongsTo('App\Loan','loan_id')->withDefault();
    }

}
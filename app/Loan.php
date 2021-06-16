<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'loans';

    public function borrower(){
    	return $this->belongsTo('App\User','borrower_id')->withDefault();
    }

    public function account(){
    	return $this->belongsTo('App\Account','account_id')->withDefault();
    }

    public function loan_product(){
        return $this->belongsTo('App\LoanProduct','loan_product_id')->withDefault();
    }

    public function approved_by(){
        return $this->belongsTo('App\User','approved_user_id')->withDefault();
    }

    public function created_by(){
        return $this->belongsTo('App\User','created_user_id')->withDefault();
    }

    public function collaterals(){
        return $this->hasMany('App\LoanCollateral','loan_id');
    }

    public function repayments(){
        return $this->hasMany('App\LoanRepayment','loan_id');
    }

    public function payments(){
        return $this->hasMany('App\LoanPayment','loan_id');
    }

    public function next_payment(){
        return $this->hasOne('App\LoanRepayment','loan_id')->where('status',0)->withDefault();
    }


}
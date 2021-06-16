<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'finance_transactions';

    public function category(){
    	return $this->belongsTo('App\ChartOfAccount','chart_of_account_id')->withDefault();
    }
}
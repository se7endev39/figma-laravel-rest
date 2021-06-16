<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card_transactions';

    public function created_user(){
    	return $this->belongsTo('App\User','created_by')->withDefault();
    }

    public function updated_user(){
    	return $this->belongsTo('App\User','updated_by')->withDefault();
    }

    public function card(){
    	return $this->belongsTo('App\Card','card_id')->withDefault();
    }

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cards';

    public function created_user(){
    	return $this->belongsTo('App\User','created_by')->withDefault();
    }

    public function updated_user(){
    	return $this->belongsTo('App\User','updated_by')->withDefault();
    }

    public function card_type(){
    	return $this->belongsTo('App\CardType','card_type_id')->withDefault();
    }

    public function owner(){
    	return $this->belongsTo('App\User','user_id')->withDefault();
    }
}
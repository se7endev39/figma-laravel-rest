<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gift_cards';

    public function currency(){
    	return $this->belongsTo('\App\Currency','currency_id')->withDefault();
    }

    public function redeem(){
    	return $this->belongsTo('\App\User','redeem_by')->withDefault();
    }
}
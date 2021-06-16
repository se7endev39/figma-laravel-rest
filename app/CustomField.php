<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'custom_fields';

    public function section(){
    	return $this->belongsTo('App\CFSection')->withDefault([
	        'section_name' => _lang('Default'),
	    ]);
    }
}
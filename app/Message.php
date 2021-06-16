<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /*
     * make dynamic attribute for human readable time
     *
     * @return string
     * */
    public function getHumansTimeAttribute()
    {
        $date = $this->created_at;
        $now = $date->now();

        return $date->diffForHumans($now, true, true);
    }

    public function conversation(){
		return $this->belongsTo('App\Conversation','conversation_id');
	}

	public function sender(){
		return $this->belongsTo('App\User','user_id');
	}

}
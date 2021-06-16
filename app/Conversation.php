<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Conversation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conversations';

    public function messages(){
		return $this->hasMany('App\Message','conversation_id');
	}

	public function sender(){
		return $this->belongsTo('App\User','sender_id');
	}

	public function receiver(){
		return $this->belongsTo('App\User','receiver_id');
	}

	public static function get_unread_inbox_count($user_id = ''){
		$id = $user_id == '' ? Auth::id() : $user_id;
		$count = Conversation::join('messages','messages.conversation_id','conversations.id')
				             ->whereRaw('(conversations.sender_id = ? OR conversations.receiver_id = ?)', [$id, $id])
				             ->where('messages.user_id','!=',$id)
				             ->where('messages.is_seen',0)
				             ->count();
        return $count;
	}

}
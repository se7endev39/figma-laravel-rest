<?php

namespace App\Listeners;

use App\Events\TransferRequestRejected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\RequestRejected as RequestRejectedNotification; //SMS Notification

class SendRequestRejectedNotification
{

    /**
     * Handle the event.
     *
     * @param  TransferRequestRejected  $event
     * @return void
     */
    public function handle(TransferRequestRejected $event)
    {
        $user = $event->transaction->user;
		
		$message = new \stdClass();
		$message->first_name = $event->transaction->user->first_name;
		$message->last_name = $event->transaction->user->last_name;
		$message->account = $event->transaction->account->account_number;
		$message->currency = $event->transaction->account->account_type->currency->name;
		$message->amount = $event->transaction->amount;
		$message->date = $event->transaction->created_at->toDateTimeString();
		
		send_message($user->id, get_option('request_rejected_subject'), get_option('request_rejected_message'), $message);
		
		try{
			//SMS Notification
			if(get_option('sms_notification') == 'yes'){
				$user->notify(new RequestRejectedNotification($message));
			}
		}catch(\Exception $e){
			//Nothing
		}
    }
}

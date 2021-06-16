<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\PaymentReceived as PaymentReceivedNotification; //SMS Notification

class SendPaymentReceivedNotification
{

    /**
     * Handle the event.
     *
     * @param  PaymentReceived  $event
     * @return void
     */
    public function handle(PaymentReceived $event)
    {
        $user = $event->credit->user;
		
		$message = new \stdClass();
		$message->first_name = $event->credit->user->first_name;
		$message->last_name = $event->credit->user->last_name;
		$message->account = $event->credit->account->account_number;
		$message->currency = $event->credit->account->account_type->currency->name;
		$message->amount = $event->credit->amount;
		$message->date = $event->credit->created_at->toDateTimeString();
		$message->payer = $event->debit->user->first_name.' '.$event->debit->user->last_name;
		
		send_message($user->id, get_option('payment_received_subject'), get_option('payment_received_message'), $message);
		
		try{
			//SMS Notification
			if(get_option('sms_notification') == 'yes'){
				$user->notify(new PaymentReceivedNotification($message));
			}
		}catch(\Exception $e){
			//Nothing
		}	
    }
}

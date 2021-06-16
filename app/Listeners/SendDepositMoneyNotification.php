<?php

namespace App\Listeners;

use App\Events\DepositMoney;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\DepositMoney as DepositMoneyNotification; //SMS Notification

class SendDepositMoneyNotification
{
    /**
     * Handle the event.
     *
     * @param  DepositMoney  $event
     * @return void
     */
    public function handle(DepositMoney $event)
    {
        $user = $event->transaction->user;
		
		$message = new \stdClass();
		$message->first_name = $event->transaction->user->first_name;
		$message->last_name = $event->transaction->user->last_name;
		$message->account = $event->transaction->account->account_number;
		$message->currency = $event->transaction->account->account_type->currency->name;
		$message->amount = $event->transaction->amount;
		$message->date = $event->transaction->created_at->toDateTimeString();
		
		send_message($user->id, get_option('deposit_subject'), get_option('deposit_message'), $message);
		
		try{
			//SMS Notification
			if(get_option('sms_notification') == 'yes'){
				$user->notify(new DepositMoneyNotification($message));
			}
		}catch(\Exception $e){
			//Nothing
		}	
	}
}

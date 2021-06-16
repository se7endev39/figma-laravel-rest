<?php

namespace App\Listeners;

use App\Events\WithdrawMoney;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\WithdrawMoney as WithdrawMoneyNotification; //SMS Notification

class SendWithdrawMoneyNotification
{

    /**
     * Handle the event.
     *
     * @param  WithdrawMoney  $event
     * @return void
     */
    public function handle(WithdrawMoney $event)
    {
        $user = $event->transaction->user;
		
		$message = new \stdClass();
		$message->first_name = $event->transaction->user->first_name;
		$message->last_name = $event->transaction->user->last_name;
		$message->account = $event->transaction->account->account_number;
		$message->currency = $event->transaction->account->account_type->currency->name;
		$message->amount = $event->transaction->amount;
		$message->date = $event->transaction->created_at->toDateTimeString();
		
		send_message($user->id, get_option('withdraw_subject'), get_option('withdraw_message'), $message);
		
		try{
			//SMS Notification
			if(get_option('sms_notification') == 'yes'){
				$user->notify(new WithdrawMoneyNotification($message));
			}
		}catch(\Exception $e){
			//Nothing
		}
    }
}

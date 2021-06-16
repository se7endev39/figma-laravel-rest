<?php

namespace App\Listeners;

use App\Events\LoanApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\LoanApproved as LoanApprovedNotification; //SMS Notification

class SendLoanApprovedNotification
{

    /**
     * Handle the event.
     *
     * @param  LoanApproved  $event
     * @return void
     */
    public function handle(LoanApproved $event)
    {
        $user = $event->transaction->user;
		
		$message = new \stdClass();
		$message->first_name = $event->transaction->user->first_name;
		$message->last_name = $event->transaction->user->last_name;
		$message->account = $event->transaction->account->account_number;
		$message->currency = $event->transaction->account->account_type->currency->name;
		$message->amount = $event->transaction->amount;
		$message->loan_id = $event->loan->loan_id;
		$message->date = $event->transaction->created_at->toDateTimeString();
		
		send_message($user->id, get_option('loan_approved_subject'), get_option('loan_approved_message'), $message);
		
		try{
			//SMS Notification
			if(get_option('sms_notification') == 'yes'){
				$user->notify(new LoanApprovedNotification($message));
			}
		}catch(\Exception $e){
			//Nothing
		}	
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\SmsMessage;

class LoanApproved extends Notification
{
    use Queueable;

	/**
     * Create a new notification instance.
     *
     * @return void
    */
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
	
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [\App\Channels\TwilioSms::class];
    }

    /**
	 * @param $notifiable
	 * @return SmsMessage
	 */
	public function toTwilioSms($notifiable)
	{
		return (new SmsMessage())
			->setContent("Hi {$this->message->first_name}, Your Loan {$this->message->loan_id} has been approved.")
			->setRecipient($notifiable->phone);
	}
}

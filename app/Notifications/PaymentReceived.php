<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Notifications\SmsMessage;

class PaymentReceived extends Notification
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
			->setContent("Hi {$this->message->first_name}, You have received a payment from {$this->message->payer}. Your Account {$this->message->account} will credited by {$this->message->currency} {$this->message->amount} within 2 business days.")
			->setRecipient($notifiable->phone);
	}
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
	private $payemnt_request;
	
    public function __construct($payemnt_request)
    {
        $this->payemnt_request = $payemnt_request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
		$url = url('/payment_request/'.encrypt($this->payemnt_request->id));
		
        return (new MailMessage)
		            ->subject(_lang('Payment Request'))
                    ->line(_lang('You have received new payment request'))
                    ->line($this->payemnt_request->description)
                    ->action(_lang('Pay Now'), $url)
                    ->line(_lang('Thank you for using our application!'));
    }

}

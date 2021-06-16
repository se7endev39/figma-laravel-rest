<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageNotification extends Notification implements ShouldQueue
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
		$url = url('/message/view_inbox/'.$this->message->conversation_id);
		
        return (new MailMessage)
		            ->subject(_lang('You have received messages from').' '.$this->message->sender->first_name)
                    ->line(_lang('You have received messages from').' '.$this->message->sender->first_name)
                    ->line($this->message->message)
                    ->action(_lang('View and Reply'), $url)
                    ->line(_lang('Thank you for using our application!'));
    }

}

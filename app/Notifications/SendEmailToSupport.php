<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailToSupport extends Notification
{
    use Queueable;

    protected $fields;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fields)
    {
        $this->fields = $fields;
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
        $fullName = $this->fields['first_name'] . " " . $this->fields['last_name'];

        return (new MailMessage)
            ->from($this->fields['email'], $fullName)
            ->subject("New support request from {$fullName}.")
            ->greeting('New support request from the site.')
            ->line('Name: ' . $fullName)
            ->line('Email: ' . $this->fields['email'])
            ->line('Phone: ' . $this->fields['phone'])
            ->line('Issue: ' . $this->fields['issue'])
            ->line('Message: ' . $this->fields['message'])
            ->line('To reply to the client at the specified email address, just click the reply button in your email client.')
            ->line('By pushing the call button, you can call the customer (only works in the smartphone).')
            ->action('Call to ' . $fullName, 'tel:' . $this->fields['phone']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

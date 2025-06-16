<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegistrationSuccessNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    // ✅ ADD THIS METHOD
    public function via($notifiable)
    {
        return ['mail'];
    }

    // ✅ ADD THIS METHOD to disable queue
    public function shouldQueue()
    {
        return false;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Registration Successful')
            ->greeting('Hello ' . $this->user->name . ',')
            ->line('You have registered successfully!')
            ->line('Details:')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('Contact: ' . $this->user->contact)
            ->line('Thank you for joining us!');
    }
}

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
            ->subject('🎉 Registration Successful')
            ->view('emails.registration_success', [
                'user' => $this->user
            ]);
    }

}

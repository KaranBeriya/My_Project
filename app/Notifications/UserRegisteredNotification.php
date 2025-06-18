<?php

namespace App\Notifications;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserRegisteredNotification extends Notification
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database']; // You can also add 'broadcast' if using websockets
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New User Registered',
            'message' => 'User ' . $this->user->name . ' has been registered.',
            'user_id' => $this->user->id,
        ];
    }
}

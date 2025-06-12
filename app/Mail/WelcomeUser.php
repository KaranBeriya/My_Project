<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class WelcomeUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $this->user->id, 'hash' => sha1($this->user->email)]
        );

        return $this->subject('Welcome to Our App')
            ->view('emails.welcome')
            ->with([
                'user' => $this->user,
                'verificationUrl' => $verificationUrl
                
            ])
            ->withSwiftMessage(function ($message) {
                // Ensure proper Content-Type is HTML
                $message->getHeaders()->addTextHeader('Content-Type', 'text/html');
            });
    }
}

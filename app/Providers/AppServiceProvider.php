<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Custom Email Verification')
                ->line('Welcome to our app! Please verify your email.')
                ->action('Click to Verify', $url)
                ->line('Thank you for using our application!');
        });
    }
}

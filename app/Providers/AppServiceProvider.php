<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
    {
        // Customiza o e-mail de verificação enviado pelo Laravel
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('KeyKeep - Confirme seu endereço de e-mail')
                ->markdown('emails.verify-email', [
                    'url'  => $url,
                    'user' => $notifiable,
                ]);
        });
    }
}

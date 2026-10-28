<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterVerificationNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = route('auth.email.verify.link', ['token' => $this->token]);

        return (new MailMessage())
            ->subject('Verifikasi email Coffenfo')
            ->greeting('Halo!')
            ->line('Klik tautan berikut untuk memverifikasi alamat email Anda. Tautan berlaku selama 24 jam.')
            ->action('Verifikasi email', $verificationUrl)
            ->line('Jika Anda tidak membuat akun Coffenfo, abaikan email ini.');
    }
}

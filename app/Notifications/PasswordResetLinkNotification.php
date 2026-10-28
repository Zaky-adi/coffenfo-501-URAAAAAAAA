<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetLinkNotification extends Notification
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
        $resetUrl = rtrim(config('app.url'), '/') . '/reset-password/' . urlencode($this->token);

        return (new MailMessage())
            ->subject('Reset kata sandi Coffenfo')
            ->greeting('Halo!')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Tautan berlaku selama 30 menit.')
            ->action('Reset kata sandi', $resetUrl)
            ->line('Jika Anda tidak meminta reset kata sandi, abaikan email ini.');
    }
}

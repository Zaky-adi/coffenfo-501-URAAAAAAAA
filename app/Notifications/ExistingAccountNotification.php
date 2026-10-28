<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExistingAccountNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Permintaan pendaftaran Coffenfo')
            ->greeting('Halo!')
            ->line('Ada permintaan pendaftaran menggunakan alamat email ini. Jika akun tersebut milik Anda, silakan masuk atau gunakan fitur lupa kata sandi.')
            ->action('Masuk ke Coffenfo', rtrim(config('app.url'), '/') . '/login')
            ->line('Jika Anda tidak membuat permintaan ini, abaikan email ini.');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Kata sandi Coffenfo berhasil diubah')
            ->greeting('Halo!')
            ->line('Kata sandi akun Coffenfo Anda baru saja diubah. Semua sesi login sebelumnya telah dicabut.')
            ->line('Jika Anda tidak melakukan perubahan ini, segera hubungi dukungan Coffenfo.');
    }
}

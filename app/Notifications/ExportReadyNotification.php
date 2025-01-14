<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ExportReadyNotification extends Notification
{
    use Queueable;

    private $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Your product export is ready!')
            ->action('Download Export', url('storage/' . $this->fileName))
            ->line('Thank you for using our application!');
    }
}

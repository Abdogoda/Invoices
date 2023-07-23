<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Add_invoice extends Notification{
    use Queueable;
    private $details;

    public function __construct($details){
        $this->details = $details;
    }

    public function via($notifiable){
        return ['mail','database'];
    }

    public function toMail($notifiable){
        return (new MailMessage)
            ->greeting($this->details['greeting'])
            ->line($this->details['body'])
            ->line($this->details['thanks']);
    }

    public function toDatabase($notifiable){
        return [
            'data' => $this->details['body']
        ];
    }
}
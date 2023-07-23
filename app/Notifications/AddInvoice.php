<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddInvoice extends Notification{
    use Queueable;
    private $invoice_id;

    public function __construct($invoice_id){
        $this->invoice_id = $invoice_id ;
    }

    public function via($notifiable){
        return ['mail','database'];
    }

    public function toMail($notifiable){
        $url = 'http://127.0.0.1:8000/invoiceDetails/'.$this->invoice_id;
        return (new MailMessage)
            ->subject('اضافة فاتورة جديدة')
            ->line('اضافة فاتورة جديدة')
            ->action('عرض الفاتورة', $url)
            ->line('شكرا لاستخدامك عبدة جودة لادارة الفواتير');
    }

    public function toDatabase($notifiable){
        return [
            'id' => $this->invoice_id,
            'title' => 'تم اضافة فاتورة جديدة بنجاح',
            'user' => Auth::user()->name
        ];
    }
}
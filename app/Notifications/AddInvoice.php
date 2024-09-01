<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;

class AddInvoice extends Notification
{
    use Queueable;
    private $invoices;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'id'=> $this->invoices->id,
            'title'=>'تم اضافة فاتورة جديد بواسطة : ',
            'user'=> Auth::user()->name,
        ];
    }
}
<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Transaction $transaction)
    {
        $this->transaction->makeVisible('token');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Payment Confirmation'))
            ->greeting(__('Hello :name, It\'s necessary to confirm your payment.', ['name' => $this->user->name]))
            ->line(__('Thank you for your purchase!'))
            ->line(__('Your payment has been successfully received.'))
            ->line(__('Payment ID: :id', ['id' => $this->transaction->id]))
            ->line(__('Amount: $:amount', ['amount' => $this->transaction->amount]))
            ->line(__('To confirm your payment, please use the following 6-digit confirmation code:'))
            ->line('**' . $this->transaction->token . '**')
            ->line(__('Please enter this code to complete your purchase.'))
            ->line(__('Thank you for shopping with us!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->transaction->only('session_id', 'token', 'amount');
    }
}

<?php

namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
// use Illuminate\Notifications\Notification;

class NewVerifyEmail extends VerifyEmail
{

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('メールアドレスの確認')
            ->line('ご登録ありがとうございます。')
            ->line('新しいメンバーが増えて、とても嬉しいです。')
            ->action('このボタンをクリック', $url)
            ->line('上記ボタンをクリックすると、ご登録が完了します！');
    }
    // use Queueable;

    // /**
    //  * Create a new notification instance.
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // /**
    //  * Get the notification's delivery channels.
    //  *
    //  * @return array<int, string>
    //  */
    // public function via(object $notifiable): array
    // {
    //     return ['mail'];
    // }

    // /**
    //  * Get the mail representation of the notification.
    //  */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @return array<string, mixed>
    //  */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class UserInvited extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = env('APP_NAME');
        $url = $this->generateInvitationUrl($notifiable->routes['mail']);

        return (new MailMessage)
                    ->subject('Personal Invitation')
                    ->greeting('Hello!')
                    ->line("You have been invited to join the {$appName} application!")
                    ->action('Click here to register your account', url($url))
                    ->line('Note: this link expires after 24 hours.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function generateInvitationUrl(string $email)
    {
        // ! Don't forget to import the URL Facade !
        return URL::temporarySignedRoute('register', now()->addDay(), [
            'email' => $email
        ]);
    }
}

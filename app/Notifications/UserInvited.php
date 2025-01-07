<?php

namespace App\Notifications;

use App\Models\Athlete;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class UserInvited extends Notification
{
    use Queueable;

    protected $athlete;

    /**
     * Create a new notification instance.
     */
    public function __construct(Athlete $athlete)
    {
        $this->athlete = $athlete;
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
        $url = $this->generateInvitationUrl($this->athlete);

        return (new MailMessage)
                    ->subject('Registrati ' . $appName)
                    ->greeting("Ciao {$this->athlete->full_name}!")
                    ->line("Sei stato invitato a registrarti su " . $appName)
                    ->action('Clicca qui per creare il tuo account', url($url))
                    ->line('Nota: questo link scadrà tra 2 settimane.');
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

    public function generateInvitationUrl(Athlete $athlete)
    {
        return URL::temporarySignedRoute('athlete.register', now()->addWeeks(2), [
            'athlete' => $athlete
        ]);
    }
}

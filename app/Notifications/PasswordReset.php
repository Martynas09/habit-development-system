<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordReset extends Notification
{
    use Queueable;
    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('Slaptažodžio atkūrimas')
        ->greeting('Sveiki!'. $notifiable->name)
        ->line('Šį el. laišką gaunate, nes gavome jūsų paskyros slaptažodžio atkūrimo užklausą.')
        ->action('Atkurti slaptažodį', url('password/reset', $this->token))
        ->line('Jei neprašėte iš naujo nustatyti slaptažodžio, jokių tolesnių veiksmų imtis nereikia.')
        ->salutation('Pagarbiai,'. config('app.name'));
        // ->subcopy('Jei turite problemų paspaudę mygtuką "Atkurti slaptažodį", nukopijuokite ir įklijuokite URL adresą žemiau į savo interneto naršyklę: [ '. url('password/reset', $this->token). ' ]('. url('password/reset', $this->token). ' )');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminder extends Notification
{
    use Queueable;
    protected $title;
    protected $minutes;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($taskTitle, $minutes)
    {
        $this->title = $taskTitle;
        $this->minutes = $minutes;
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
            ->subject('Užduoties priminimas')
            ->greeting('Sveiki!' . $notifiable->name)
            ->line('Jūsų užduotis: ' . $this->title)
            ->line('Prasidės už: ' . $this->minutes . ' min.')
            ->salutation('Įpročių ugdymo sistema');
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

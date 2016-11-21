<?php

namespace App\Notifications;

use App\User;
use App\Incident;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public $incident;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Incident $incident)
    {
        $this->incident = $incident;
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
            ->greeting('Hi,')
            ->subject(config('site.title')  . ' Submission: '. $this->incident->title)
            ->line('An incident was submitted to ' . config('site.title') . ':')
            ->line('Title:')
            ->line('"' . $this->incident->title . '"')
            ->line('Description:')
            ->line(str_limit($this->incident->description, config('site.short_description_length')))
            ->action('Moderate It', $this->incident->admin_url)
            ->line('Thank you for helping us out.');
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

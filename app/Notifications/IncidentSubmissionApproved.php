<?php

namespace App\Notifications;

use App\Incident;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class IncidentSubmissionApproved extends Notification implements ShouldQueue
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
            ->subject(config('site.title')  . ' Submission Approved')
            ->greeting('Hi,')
            ->line('We have approved the incident you submitted to ' . config('site.title') . ':')
            ->line('"' . $this->incident->title . '"')
            ->action('View It', $this->incident->url)
            ->line('Thank you for your help.');
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

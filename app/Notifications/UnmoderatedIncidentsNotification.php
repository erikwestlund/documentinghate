<?php

namespace App\Notifications;

use App\Incident;
use Carbon\Carbon;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UnmoderatedIncidentsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $incidents;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->incidents = Incident::unmoderated()
            ->get();
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
        $message = (new MailMessage)
            ->subject(config('site.title') . ': Unmoderated Incidents Notification')
            ->greeting('Hi,')
            ->line('There are ' . $this->incidents->count() . ' unmoderated incidents at ' . config('site.title'). '.')
            ->line('Would you help us moderate them?')
            ->line('Here\'s a preview:');

        foreach($this->incidents->take(5) as $incident) {
            $message = $message->line('   * ' . $incident->title);    
        }

        return $message->action('Moderate Them', secure_url('/admin/incidents'))
            ->line('Thank you for your help');

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

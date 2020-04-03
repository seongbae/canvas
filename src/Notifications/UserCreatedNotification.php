<?php

namespace Seongbae\Canvas\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreatedNotification extends Notification
{
    use Queueable;

    private $user;
    private $args;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $args=[])
    {
        $this->user = $user;
        $this->args = $args;
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
        $mailMsg = new MailMessage;
        $mailMsg->from(option('from_email',config('mail.from.address')), option('from_name',config('mail.from.name')));
        $mailMsg->subject('New account created');
        $mailMsg->line('Your account has been created on '.option('site_name',config('app.name')));
        $mailMsg->line('Username: ' . $this->user->email);

        if (array_key_exists('password', $this->args))
            $mailMsg->line('Password: ' . $this->args['password']);

        $mailMsg->action('Go to my account', url('/login'));
        $mailMsg->markdown('emails.email');

        return $mailMsg;
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

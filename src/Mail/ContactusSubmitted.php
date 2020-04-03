<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Registration;

class ContactusSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    private $email, $name, $phone, $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $phone, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(option('from_email'), option('from_name'))
                ->subject('Form submission on '.option('site_name'))
                ->replyTo($this->email, $this->name)
                ->with('email', $this->email)
                ->with('name', $this->name)
                ->with('phone', $this->phone)
                ->with('message', $this->message)
                ->markdown('emails.website.contactus');
    }
}

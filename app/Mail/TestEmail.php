<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = 'Test SMTP Config';
    public $body = 'Testing Laravel SMTP config from nickandollie.com rebuild.';

    public function __construct()
    {
        //
    }

    public function build()
    {
        return $this->text('mail.test', ['body' => $this->body])
            ->subject($this->subject);
    }
}

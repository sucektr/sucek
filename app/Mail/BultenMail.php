<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BultenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $konu,
        public string $icerik,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->konu);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.bulten');
    }
}

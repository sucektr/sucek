<?php

namespace App\Mail;

use App\Models\Siparis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiparisOnayMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Siparis $siparis) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Siparişiniz Alındı — #' . $this->siparis->referans,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.siparis-onay',
        );
    }
}

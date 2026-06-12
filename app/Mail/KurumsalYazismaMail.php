<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KurumsalYazismaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $konu,
        public string $icerik,
        public string $imzaAd,
        public string $imzaGorev,
        public string $imzaTel,
        public string $imzaEmail,
        public string $imzaAdres = '',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->konu);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.yazisma');
    }
}

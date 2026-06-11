<?php

namespace App\Mail;

use App\Models\Haber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HaberDuyuruMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Haber $haber) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yeni Haber: ' . $this->haber->baslik,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.haber-duyuru',
        );
    }
}

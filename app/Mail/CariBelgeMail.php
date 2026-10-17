<?php

namespace App\Mail;

use App\Models\CariBelge;
use App\Models\Musteri;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CariBelgeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Musteri $musteri,
        public CariBelge $belge,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'SUÇEK — ' . $this->belge->dosya_adi);
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.cari-belge',
            with: ['musteri' => $this->musteri, 'belge' => $this->belge],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('public', $this->belge->dosya_yolu)->as($this->belge->dosya_adi),
        ];
    }
}

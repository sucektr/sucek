<?php

namespace App\Mail;

use App\Models\Musteri;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CariEkstreMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Musteri $musteri,
        public string $pdfIcerik,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Cari Hesap Ekstreniz');
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.cari-ekstre',
            with: ['musteri' => $this->musteri],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfIcerik, 'cari-ekstre-' . str($this->musteri->ad_soyad)->slug() . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

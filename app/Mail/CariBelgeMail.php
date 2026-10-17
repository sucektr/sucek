<?php

namespace App\Mail;

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
        public \Illuminate\Support\Collection $belgeler,
    ) {}

    public function envelope(): Envelope
    {
        $konu = $this->belgeler->count() === 1
            ? 'SUÇEK — ' . $this->belgeler->first()->dosya_adi
            : 'SUÇEK — ' . $this->belgeler->count() . ' Belge';

        return new Envelope(subject: $konu);
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.cari-belge',
            with: ['musteri' => $this->musteri, 'belgeler' => $this->belgeler],
        );
    }

    public function attachments(): array
    {
        return $this->belgeler
            ->map(fn ($belge) => Attachment::fromStorageDisk('public', $belge->dosya_yolu)->as($belge->dosya_adi))
            ->all();
    }
}

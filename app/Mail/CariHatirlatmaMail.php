<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CariHatirlatmaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $musteriHareketleri,
        public Collection $tedarikciHareketleri,
    ) {}

    public function envelope(): Envelope
    {
        $toplam = $this->musteriHareketleri->count() + $this->tedarikciHareketleri->count();

        return new Envelope(subject: 'Cari Hesap Hatırlatma — ' . $toplam . ' Ödeme');
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.cari-hatirlatma',
            with: [
                'musteriHareketleri' => $this->musteriHareketleri,
                'tedarikciHareketleri' => $this->tedarikciHareketleri,
            ],
        );
    }
}

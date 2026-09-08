<?php

namespace App\Mail;

use App\Models\Siparis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiparisBildirimMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $baslik;
    public string $mesaj;

    public function __construct(public Siparis $siparis, string $asama)
    {
        $map = [
            'olusturuldu'  => ['Yeni Sipariş Alındı', 'Sitenizden yeni bir sipariş oluşturuldu.'],
            'odeme_alindi' => ['Sipariş Ödemesi Alındı', 'Bir siparişin ödemesi onaylandı, hazırlanmaya hazır.'],
        ];

        $this->baslik = $map[$asama][0] ?? 'Sipariş Bildirimi';
        $this->mesaj  = $map[$asama][1] ?? '';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->baslik . ' — #' . $this->siparis->referans,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.siparis-bildirim',
        );
    }
}

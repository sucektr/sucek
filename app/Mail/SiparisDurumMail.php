<?php

namespace App\Mail;

use App\Models\Siparis;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiparisDurumMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $durumBaslik;
    public string $durumMesaj;

    public function __construct(public Siparis $siparis)
    {
        $map = [
            'odeme_alindi'  => ['Ödemeniz Alındı',          'Ödemeniz onaylandı, siparişiniz hazırlanmaya başlıyor.'],
            'hazirlaniyor'  => ['Siparişiniz Hazırlanıyor', 'Siparişiniz özenle hazırlanıyor, yakında kargoya verilecek.'],
            'kargolandi'    => ['Siparişiniz Kargoya Verildi', 'Siparişiniz kargo firmasına teslim edildi.'],
            'teslim_edildi' => ['Siparişiniz Teslim Edildi', 'Siparişiniz başarıyla teslim edildi. İyi günlerde kullanın!'],
            'iptal'         => ['Siparişiniz İptal Edildi', 'Siparişiniz iptal edildi. Detay için bizimle iletişime geçebilirsiniz.'],
        ];

        $this->durumBaslik = $map[$siparis->durum][0] ?? 'Sipariş Durumu Güncellendi';
        $this->durumMesaj  = $map[$siparis->durum][1] ?? '';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->durumBaslik . ' — #' . $this->siparis->referans,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.siparis-durum',
        );
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CariEkstreMail;
use App\Models\CariHareket;
use App\Models\Icerik;
use App\Models\Musteri;
use App\Services\MailService;
use App\Services\SmsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CariController extends Controller
{
    public function index(Request $request)
    {
        $arama = $request->get('q');

        $musteriler = Musteri::query()
            ->when($arama, fn ($q) => $q->where('ad_soyad', 'like', "%{$arama}%"))
            ->orderBy('ad_soyad')
            ->get()
            ->map(function (Musteri $musteri) {
                $musteri->hesaplanan_bakiye = $musteri->bakiye();
                return $musteri;
            });

        $toplamBakiye = $musteriler->sum('hesaplanan_bakiye');

        $vadesiYaklasanlar = CariHareket::with('musteri')
            ->where('tur', 'borc')
            ->where('odendi_mi', false)
            ->whereNotNull('vade_tarihi')
            ->whereDate('vade_tarihi', '<=', now()->addDays(3))
            ->orderBy('vade_tarihi')
            ->get();

        $hatirlatmaAyarlari = [
            'email'   => icerik('sistem', 'cari_hatirlatma_email', 'info@sucek.com.tr'),
            'telefon' => icerik('sistem', 'cari_hatirlatma_telefon'),
        ];

        return view('admin.cari.index', compact('musteriler', 'toplamBakiye', 'vadesiYaklasanlar', 'hatirlatmaAyarlari'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad_soyad'      => 'required|string|max:150',
            'telefon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:150',
            'adres'         => 'nullable|string|max:500',
            'vergi_dairesi' => 'nullable|string|max:100',
            'vergi_no'      => 'nullable|string|max:50',
            'notlar'        => 'nullable|string|max:1000',
        ]);

        $musteri = Musteri::create($data);

        return redirect()->route('admin.cari.show', $musteri)->with('basari', '"' . $musteri->ad_soyad . '" cari hesabı oluşturuldu.');
    }

    public function show(Musteri $musteri)
    {
        $hareketler = $musteri->hareketler()->get();
        $bakiye     = $musteri->bakiye();

        return view('admin.cari.show', compact('musteri', 'hareketler', 'bakiye'));
    }

    public function update(Request $request, Musteri $musteri)
    {
        $data = $request->validate([
            'ad_soyad'      => 'required|string|max:150',
            'telefon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:150',
            'adres'         => 'nullable|string|max:500',
            'vergi_dairesi' => 'nullable|string|max:100',
            'vergi_no'      => 'nullable|string|max:50',
            'notlar'        => 'nullable|string|max:1000',
            'aktif'         => 'nullable|boolean',
        ]);
        $data['aktif'] = $request->boolean('aktif', true);

        $musteri->update($data);

        return back()->with('basari', 'Müşteri bilgileri güncellendi.');
    }

    public function hareketEkle(Request $request, Musteri $musteri)
    {
        $data = $request->validate([
            'tur'         => 'required|in:borc,tahsilat',
            'tutar'       => 'required|numeric|min:0.01',
            'aciklama'    => 'required|string|max:200',
            'tarih'       => 'required|date',
            'vade_tarihi' => 'nullable|date',
        ]);

        $musteri->hareketler()->create($data);

        return back()->with('basari', 'Hareket kaydedildi.');
    }

    public function hareketGuncelle(Request $request, Musteri $musteri, CariHareket $hareket)
    {
        abort_unless($hareket->musteri_id === $musteri->id, 404);

        if ($request->has('odendi_mi') && !$request->has('tutar')) {
            $hareket->update(['odendi_mi' => $request->boolean('odendi_mi')]);
            return back()->with('basari', 'Hareket durumu güncellendi.');
        }

        $data = $request->validate([
            'tur'         => 'required|in:borc,tahsilat',
            'tutar'       => 'required|numeric|min:0.01',
            'aciklama'    => 'required|string|max:200',
            'tarih'       => 'required|date',
            'vade_tarihi' => 'nullable|date',
        ]);

        $hareket->update($data);

        return back()->with('basari', 'Hareket güncellendi.');
    }

    public function hareketSil(Musteri $musteri, CariHareket $hareket)
    {
        abort_unless($hareket->musteri_id === $musteri->id, 404);

        $hareket->delete();

        return back()->with('basari', 'Hareket silindi.');
    }

    public function ekstrePdf(Musteri $musteri)
    {
        $pdf = Pdf::loadView('admin.cari.pdf.ekstre', [
            'musteri'    => $musteri,
            'hareketler' => $musteri->hareketler()->get(),
            'bakiye'     => $musteri->bakiye(),
        ]);

        return $pdf->download('cari-ekstre-' . str($musteri->ad_soyad)->slug() . '.pdf');
    }

    public function ekstreGonder(Request $request, Musteri $musteri, MailService $mail, SmsService $sms)
    {
        $request->validate([
            'mail_gonder' => 'nullable|boolean',
            'sms_gonder'  => 'nullable|boolean',
        ]);

        if (!$musteri->email && !$musteri->telefon) {
            return back()->with('hata', 'Müşterinin kayıtlı e-posta veya telefonu yok.');
        }

        $pdf = Pdf::loadView('admin.cari.pdf.ekstre', [
            'musteri'    => $musteri,
            'hareketler' => $musteri->hareketler()->get(),
            'bakiye'     => $musteri->bakiye(),
        ]);

        $mailGonderildi = false;
        $smsGonderildi  = false;

        if ($request->boolean('mail_gonder', true) && $musteri->email) {
            $mailGonderildi = $mail->gonder(
                $musteri->email,
                new CariEkstreMail($musteri, $pdf->output()),
                'cari_ekstre'
            );
        }

        if ($request->boolean('sms_gonder') && $musteri->telefon) {
            $smsGonderildi = $sms->gonder(
                $musteri->telefon,
                'Sayın ' . $musteri->ad_soyad . ', cari hesap ekstreniz e-posta adresinize gönderildi. Bilgi: SUÇEK',
                'cari_ekstre'
            );
        }

        if (!$mailGonderildi && !$smsGonderildi) {
            return back()->with('hata', 'Ekstre gönderilemedi.');
        }

        return back()->with('basari', 'Ekstre gönderildi.');
    }

    public function ayarlarKaydet(Request $request)
    {
        $request->validate([
            'cari_hatirlatma_email'   => 'nullable|email|max:150',
            'cari_hatirlatma_telefon' => 'nullable|string|max:20',
        ]);

        foreach (['cari_hatirlatma_email', 'cari_hatirlatma_telefon'] as $alan) {
            Icerik::updateOrCreate(
                ['sayfa' => 'sistem', 'alan' => $alan],
                ['deger' => $request->input($alan), 'baslik' => $alan, 'tip' => 'metin', 'sira' => 0]
            );
        }

        return back()->with('basari', 'Hatırlatma ayarları kaydedildi.');
    }
}

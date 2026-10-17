<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CariBelgeMail;
use App\Mail\CariEkstreMail;
use App\Models\CariBelge;
use App\Models\CariHareket;
use App\Models\Icerik;
use App\Models\Musteri;
use App\Services\MailService;
use App\Services\SmsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CariController extends Controller
{
    public function anasayfa()
    {
        $musteriler = Musteri::where('tip', 'musteri')->get()
            ->map(function (Musteri $m) {
                $m->hesaplanan_bakiye = $m->bakiye();
                return $m;
            });

        $tedarikciler = Musteri::where('tip', 'tedarikci')->get()
            ->map(function (Musteri $t) {
                $t->hesaplanan_bakiye = $t->bakiye();
                return $t;
            });

        $toplamBakiye = $musteriler->sum('hesaplanan_bakiye');
        $toplamBorc   = $tedarikciler->sum('hesaplanan_bakiye');

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

        $grafik = $this->grafikVerisi();

        $enYuksekBorclular = $musteriler
            ->filter(fn (Musteri $m) => $m->hesaplanan_bakiye > 0)
            ->sortByDesc('hesaplanan_bakiye')
            ->take(5)
            ->values();

        return view('admin.cari.index', compact(
            'toplamBakiye', 'toplamBorc', 'vadesiYaklasanlar', 'hatirlatmaAyarlari', 'grafik', 'enYuksekBorclular'
        ));
    }

    public function musteriIndex(Request $request)
    {
        $arama = $request->get('q');

        $musteriler = Musteri::query()
            ->where('tip', 'musteri')
            ->when($arama, fn ($q) => $q->where('ad_soyad', 'like', "%{$arama}%"))
            ->orderBy('ad_soyad')
            ->get()
            ->map(function (Musteri $musteri) {
                $musteri->hesaplanan_bakiye = $musteri->bakiye();
                return $musteri;
            });

        return view('admin.cari.musteriler', compact('musteriler'));
    }

    public function tedarikciIndex(Request $request)
    {
        $arama = $request->get('q');

        $tedarikciler = Musteri::query()
            ->where('tip', 'tedarikci')
            ->when($arama, fn ($q) => $q->where('ad_soyad', 'like', "%{$arama}%"))
            ->orderBy('ad_soyad')
            ->get()
            ->map(function (Musteri $t) {
                $t->hesaplanan_bakiye = $t->bakiye();
                return $t;
            });

        return view('admin.cari.tedarikci.index', compact('tedarikciler'));
    }

    private function grafikVerisi(): array
    {
        $turkceAylar = ['Oca', 'Şub', 'Mar', 'Nis', 'May', 'Haz', 'Tem', 'Ağu', 'Eyl', 'Eki', 'Kas', 'Ara'];
        $aylar = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i)->startOfMonth());

        $etiketler      = [];
        $bakiyeTrend    = [];
        $aylikBorc      = [];
        $aylikTahsilat  = [];

        $musteriTip = fn ($q) => $q->whereHas('musteri', fn ($qq) => $qq->where('tip', 'musteri'));

        foreach ($aylar as $ay) {
            $etiketler[] = $turkceAylar[$ay->month - 1] . ' ' . $ay->format('y');

            $bakiyeTrend[] = (float) CariHareket::tap($musteriTip)
                ->where('tarih', '<=', $ay->copy()->endOfMonth())
                ->selectRaw("COALESCE(SUM(CASE WHEN tur = 'borc' THEN tutar ELSE -tutar END), 0) as b")
                ->value('b');

            $araligBasi = $ay->copy()->startOfMonth();
            $araligSonu = $ay->copy()->endOfMonth();

            $aylikBorc[] = (float) CariHareket::tap($musteriTip)->where('tur', 'borc')
                ->whereBetween('tarih', [$araligBasi, $araligSonu])->sum('tutar');

            $aylikTahsilat[] = (float) CariHareket::tap($musteriTip)->where('tur', 'tahsilat')
                ->whereBetween('tarih', [$araligBasi, $araligSonu])->sum('tutar');
        }

        return compact('etiketler', 'bakiyeTrend', 'aylikBorc', 'aylikTahsilat');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ad_soyad'      => 'required|string|max:150',
            'tip'           => 'nullable|in:musteri,tedarikci',
            'yetkili_kisi'  => 'nullable|string|max:150',
            'telefon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:150',
            'adres'         => 'nullable|string|max:500',
            'vergi_dairesi' => 'nullable|string|max:100',
            'vergi_no'      => 'nullable|string|max:50',
            'notlar'        => 'nullable|string|max:1000',
        ]);
        $data['tip'] = $data['tip'] ?? 'musteri';

        $musteri = Musteri::create($data);

        return redirect()->route('admin.cari.show', $musteri)->with('basari', '"' . $musteri->ad_soyad . '" cari hesabı oluşturuldu.');
    }

    public function show(Musteri $musteri)
    {
        $hareketler = $musteri->hareketler()->get();
        $bakiye     = $musteri->bakiye();
        $belgeler   = $musteri->belgeler()->get();

        return view('admin.cari.show', compact('musteri', 'hareketler', 'bakiye', 'belgeler'));
    }

    public function update(Request $request, Musteri $musteri)
    {
        $data = $request->validate([
            'ad_soyad'      => 'required|string|max:150',
            'tip'           => 'required|in:musteri,tedarikci',
            'yetkili_kisi'  => 'nullable|string|max:150',
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

        return back()->with('basari', 'Kayıt güncellendi.');
    }

    public function destroy(Musteri $musteri)
    {
        $adSoyad = $musteri->ad_soyad;
        $hedefRota = $musteri->tip === 'tedarikci' ? 'admin.cari.tedarikci.index' : 'admin.cari.index';

        Storage::disk('public')->deleteDirectory('cari-belgeler/' . $musteri->id);
        $musteri->delete();

        return redirect()->route($hedefRota)->with('basari', '"' . $adSoyad . '" cari hesabı silindi.');
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

    public function belgeYukle(Request $request, Musteri $musteri)
    {
        $request->validate([
            'dosya' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ]);

        $dosya = $request->file('dosya');
        $yol   = $dosya->store('cari-belgeler/' . $musteri->id, 'public');

        $musteri->belgeler()->create([
            'dosya_adi'    => $dosya->getClientOriginalName(),
            'dosya_yolu'   => $yol,
            'dosya_boyutu' => $dosya->getSize(),
            'mime_tipi'    => $dosya->getClientMimeType(),
        ]);

        return back()->with('basari', 'Belge yüklendi.');
    }

    public function belgeSil(Musteri $musteri, CariBelge $belge)
    {
        abort_unless($belge->musteri_id === $musteri->id, 404);

        Storage::disk('public')->delete($belge->dosya_yolu);
        $belge->delete();

        return back()->with('basari', 'Belge silindi.');
    }

    public function belgeGonder(Musteri $musteri, CariBelge $belge, MailService $mail, SmsService $sms)
    {
        abort_unless($belge->musteri_id === $musteri->id, 404);

        if (!$musteri->email) {
            return back()->with('hata', 'Müşterinin kayıtlı e-postası yok, belge gönderilemedi.');
        }

        $mailGonderildi = $mail->gonder($musteri->email, new CariBelgeMail($musteri, $belge), 'cari_belge');

        if ($mailGonderildi && $musteri->telefon) {
            $sms->gonder(
                $musteri->telefon,
                'Sayın ' . $musteri->ad_soyad . ', size yeni bir belge e-posta ile gönderildi. Bilgi: SUÇEK',
                'cari_belge'
            );
        }

        return back()->with(
            $mailGonderildi ? 'basari' : 'hata',
            $mailGonderildi ? 'Belge "' . $musteri->email . '" adresine gönderildi.' : 'Belge gönderilemedi.'
        );
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

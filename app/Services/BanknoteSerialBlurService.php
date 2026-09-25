<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Throwable;

/**
 * Kağıt para (banknot) görsellerinde seri numarasını Google Cloud Vision
 * (TEXT_DETECTION) ile tespit edip pikselleştirerek okunmaz hale getirir.
 */
class BanknoteSerialBlurService
{
    // Seri numaraları genelde 0-3 harf + 5-10 rakam + 0-2 harf şeklindedir (TL, USD, EUR vb.).
    private const SERIAL_PATTERN = '/^[A-Z]{0,3}[0-9]{5,10}[A-Z]{0,2}$/';
    private const PADDING = 6;
    private const PIXEL_SIZE = 18;

    public function __construct(private Client $http)
    {
    }

    /**
     * Verilen "public" disk yolundaki görseli işler; seri numarası bulunursa
     * üzerine yazar ve orijinalini "local" diskte yedekler.
     *
     * @return bool Seri numarası bulunup blurlandıysa true.
     */
    public function blur(string $publicDiskPath): bool
    {
        $apiKey = config('services.google_vision.key');
        if (!$apiKey) {
            Log::warning('BanknoteSerialBlurService: GOOGLE_VISION_API_KEY tanımlı değil, blur atlanıyor.', [
                'path' => $publicDiskPath,
            ]);
            return false;
        }

        $fullPath = Storage::disk('public')->path($publicDiskPath);
        if (!is_file($fullPath)) {
            return false;
        }

        $boxes = $this->detectSerialBoxes($fullPath, $apiKey);
        if (empty($boxes)) {
            return false;
        }

        // Blurlamadan önce orijinali özel (public olmayan) diske yedekle.
        Storage::disk('local')->put(
            'koleksiyonlar_orijinal/'.basename($publicDiskPath),
            file_get_contents($fullPath)
        );

        $manager = new ImageManager(new Driver());
        $image = $manager->read($fullPath);

        foreach ($boxes as $box) {
            $region = (clone $image)->crop($box['w'], $box['h'], $box['x'], $box['y']);
            $region->pixelate(self::PIXEL_SIZE);
            $image->place($region, 'top-left', $box['x'], $box['y']);
        }

        $image->save($fullPath);

        return true;
    }

    /**
     * @return array<int, array{x:int,y:int,w:int,h:int}>
     */
    private function detectSerialBoxes(string $fullPath, string $apiKey): array
    {
        try {
            $response = $this->http->post('https://vision.googleapis.com/v1/images:annotate', [
                'query' => ['key' => $apiKey],
                'json' => [
                    'requests' => [[
                        'image' => ['content' => base64_encode(file_get_contents($fullPath))],
                        'features' => [['type' => 'TEXT_DETECTION']],
                    ]],
                ],
                'timeout' => 20,
            ]);
        } catch (Throwable $e) {
            Log::warning('BanknoteSerialBlurService: Vision API isteği başarısız oldu.', [
                'path' => $fullPath,
                'error' => $e->getMessage(),
            ]);
            return [];
        }

        $data = json_decode((string) $response->getBody(), true);
        $annotations = $data['responses'][0]['textAnnotations'] ?? [];
        if (count($annotations) < 2) {
            return [];
        }

        $size = getimagesize($fullPath);
        if (!$size) {
            return [];
        }
        [$imgWidth, $imgHeight] = $size;

        $boxes = [];
        // İlk eleman görseldeki tüm metnin birleşimi, kelime bazlı taramaya 1'den başla.
        foreach (array_slice($annotations, 1) as $word) {
            $text = strtoupper(trim($word['description'] ?? ''));
            if (!preg_match(self::SERIAL_PATTERN, $text)) {
                continue;
            }

            $vertices = $word['boundingPoly']['vertices'] ?? [];
            if (count($vertices) < 4) {
                continue;
            }

            $xs = [];
            $ys = [];
            foreach ($vertices as $v) {
                $xs[] = (int) ($v['x'] ?? 0);
                $ys[] = (int) ($v['y'] ?? 0);
            }

            $x = max(0, min($xs) - self::PADDING);
            $y = max(0, min($ys) - self::PADDING);
            $w = min($imgWidth - $x, (max($xs) - min($xs)) + self::PADDING * 2);
            $h = min($imgHeight - $y, (max($ys) - min($ys)) + self::PADDING * 2);

            if ($w > 0 && $h > 0) {
                $boxes[] = ['x' => $x, 'y' => $y, 'w' => $w, 'h' => $h];
            }
        }

        return $boxes;
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramService
{
    public function getPosts(int $limit = 5): array
    {
        $token = config('services.instagram.access_token');

        if (!$token) {
            return [];
        }

        return Cache::remember('instagram_feed', 3600, function () use ($token, $limit) {
            try {
                $response = Http::timeout(8)->get('https://graph.instagram.com/me/media', [
                    'fields'       => 'id,media_type,media_url,thumbnail_url,permalink,caption,timestamp',
                    'access_token' => $token,
                    'limit'        => $limit * 2, // fazla çek, filtreden sonra limit'e indir
                ]);

                if ($response->failed()) {
                    Log::warning('Instagram API hatası', ['status' => $response->status()]);
                    return [];
                }

                return collect($response->json('data', []))
                    ->filter(fn($p) => in_array($p['media_type'], ['IMAGE', 'CAROUSEL_ALBUM']))
                    ->take($limit)
                    ->values()
                    ->toArray();
            } catch (\Exception $e) {
                Log::warning('Instagram feed yüklenemedi: ' . $e->getMessage());
                return [];
            }
        });
    }
}

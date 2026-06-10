<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoyAgaciSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/soy-agaci.json');

        if (!file_exists($path)) {
            $this->command->error('JSON dosyası bulunamadı: ' . $path);
            return;
        }

        $json = json_decode(file_get_contents($path), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('JSON parse hatası: ' . json_last_error_msg());
            return;
        }

        $mevcutSayisi = DB::table('soy_agaci_kisiler')->count();
        if ($mevcutSayisi > 0) {
            $this->command->warn("Veritabanında zaten {$mevcutSayisi} kişi var. Mevcut veriler silinip yeniden yüklenecek.");
            if (!$this->command->confirm('Devam etmek istiyor musunuz?', false)) {
                $this->command->info('İptal edildi.');
                return;
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('soy_agaci_gruplar')->truncate();
        DB::table('soy_agaci_iliskiler')->truncate();
        DB::table('soy_agaci_degisiklikler')->truncate();
        DB::table('soy_agaci_kisiler')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Kişileri ekle, JSON id → DB id eşlemesi tut
        $idMap = [];

        foreach ($json['people'] as $person) {
            $dbId = DB::table('soy_agaci_kisiler')->insertGetId([
                'ad'         => $person['ad'],
                'soyad'      => ($person['soyad'] !== '' && $person['soyad'] !== '?') ? $person['soyad'] : null,
                'cinsiyet'   => $person['gender'] ?? 'unknown',
                'meslek'     => $person['meslek'] ?: null,
                'bd_gun'     => $person['bdGun'] ?: null,
                'bd_ay'      => $person['bdAy'] ?: null,
                'bd_yil'     => $person['bdYil'] ?: null,
                'olum_yil'   => $person['olum'] ?: null,
                'yer'        => $person['yer'] ?: null,
                'notlar'     => $person['not'] ?: null,
                'foto'       => $person['photo'] ?? null,
                'konum_x'    => $person['x'] ?? 0,
                'konum_y'    => $person['y'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $idMap[$person['id']] = $dbId;
        }

        // İlişkileri ekle
        $iliski = 0;
        foreach ($json['relations'] as $rel) {
            $kisi1 = $idMap[$rel['from']] ?? null;
            $kisi2 = $idMap[$rel['to']] ?? null;
            if (!$kisi1 || !$kisi2) continue;

            DB::table('soy_agaci_iliskiler')->insert([
                'kisi1_id'   => $kisi1,
                'kisi2_id'   => $kisi2,
                'tip'        => $rel['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $iliski++;
        }

        // Grupları (frames) ekle
        $gruplar = 0;
        foreach ($json['frames'] ?? [] as $frame) {
            $uyeIdler = array_values(array_filter(
                array_map(fn($id) => $idMap[$id] ?? null, $frame['memberIds'])
            ));

            DB::table('soy_agaci_gruplar')->insert([
                'label'      => $frame['label'],
                'renk_hex'   => $frame['color']['hex'],
                'renk_bg'    => $frame['color']['bg'],
                'uye_idler'  => json_encode($uyeIdler),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $gruplar++;
        }

        $this->command->info('✓ Soy ağacı aktarıldı:');
        $this->command->info('  • ' . count($json['people']) . ' kişi');
        $this->command->info('  • ' . $iliski . ' ilişki');
        $this->command->info('  • ' . $gruplar . ' grup');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            IcerikSeeder::class,
            UrunSeeder::class,
            KoleksiyonSeeder::class,
            ProjeSeeder::class,
            HaberSeeder::class,
        ]);
    }
}

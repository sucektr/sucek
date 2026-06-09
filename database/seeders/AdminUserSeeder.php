<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'info@sucek.com.tr'],
            [
                'name'              => 'SUÇEK Admin',
                'email'             => 'info@sucek.com.tr',
                'password'          => Hash::make('sucek2024!'),
                'is_admin'          => true,
                'rol'               => 'sucek',
                'email_verified_at' => now(),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        );
    }
}

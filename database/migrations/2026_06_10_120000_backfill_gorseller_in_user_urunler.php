<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('user_urunler')
            ->whereNull('gorseller')
            ->whereNotNull('gorsel')
            ->get(['id', 'gorsel'])
            ->each(function ($row) {
                DB::table('user_urunler')
                    ->where('id', $row->id)
                    ->update(['gorseller' => json_encode([$row->gorsel])]);
            });
    }

    public function down(): void {}
};

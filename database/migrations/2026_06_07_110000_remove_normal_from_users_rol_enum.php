<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE users SET rol = 'standart' WHERE rol = 'normal'");
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('standart','sucek','teknik') NOT NULL DEFAULT 'standart'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('normal','standart','sucek','teknik') NOT NULL DEFAULT 'normal'");
    }
};

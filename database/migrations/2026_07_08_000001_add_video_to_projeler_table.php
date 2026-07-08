<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projeler', function (Blueprint $table) {
            $table->string('video_url', 500)->nullable()->after('detaylar');
            $table->string('video', 300)->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('projeler', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video']);
        });
    }
};

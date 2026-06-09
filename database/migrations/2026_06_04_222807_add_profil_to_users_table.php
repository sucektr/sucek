<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefon', 20)->nullable()->after('rol');
            $table->tinyInteger('dogum_gun')->unsigned()->nullable()->after('telefon');
            $table->tinyInteger('dogum_ay')->unsigned()->nullable()->after('dogum_gun');
            $table->smallInteger('dogum_yil')->unsigned()->nullable()->after('dogum_ay');
            $table->boolean('sms_izni')->default(false)->after('dogum_yil');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telefon', 'dogum_gun', 'dogum_ay', 'dogum_yil', 'sms_izni']);
        });
    }
};

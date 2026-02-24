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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insérer les paramètres par défaut
        DB::table('settings')->insert([
            ['key' => 'rappel_jours_avant', 'value' => '7'],
            ['key' => 'rappel_heures', 'value' => '09:00'],
            ['key' => 'langue_fr_active', 'value' => '1'],
            ['key' => 'langue_fon_active', 'value' => '1'],
            ['key' => 'langue_yoruba_active', 'value' => '1'],
            ['key' => 'email_support', 'value' => 'support@carnetsante.bj'],
            ['key' => 'telephone_support', 'value' => '+229 00 00 00 00'],
            ['key' => 'notification_sms_active', 'value' => '1'],
            ['key' => 'notification_email_active', 'value' => '1'],
            ['key' => 'delai_rappel_j1', 'value' => '7'],
            ['key' => 'delai_rappel_j2', 'value' => '1'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
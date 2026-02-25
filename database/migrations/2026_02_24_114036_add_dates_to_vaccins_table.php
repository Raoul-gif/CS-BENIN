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
        Schema::table('vaccins', function (Blueprint $table) {
            if (!Schema::hasColumn('vaccins', 'date_debut')) {
                $table->date('date_debut')->nullable()->after('maladie_previent');
            }
            if (!Schema::hasColumn('vaccins', 'date_fin')) {
                $table->date('date_fin')->nullable()->after('date_debut');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccins', function (Blueprint $table) {
            $table->dropColumn(['date_debut', 'date_fin']);
        });
    }
};
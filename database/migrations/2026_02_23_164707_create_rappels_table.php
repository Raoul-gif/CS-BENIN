<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rappels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enfant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vaccin_id')->constrained()->onDelete('cascade');
            $table->date('date_prevue');
            $table->date('date_administration')->nullable();
            $table->enum('statut', ['en_attente', 'effectue', 'reporte', 'annule'])->default('en_attente');
            $table->string('lot_vaccin')->nullable();
            $table->string('centre_sante')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rappels');
    }
};
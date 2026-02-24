<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historique_vaccins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enfant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vaccin_id')->constrained();
            $table->date('date_administration');
            $table->string('lieu_administration');
            $table->string('professionnel_sante')->nullable();
            $table->string('lot_vaccin')->nullable();
            $table->date('prochain_rappel')->nullable();
            $table->text('notes')->nullable();
            $table->string('document_justificatif')->nullable(); // chemin du fichier
            $table->timestamps();
            
            // Index pour recherches rapides
            $table->index(['enfant_id', 'date_administration']);
            $table->index('prochain_rappel');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_vaccins');
    }
};
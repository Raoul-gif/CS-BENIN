<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carnets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enfant_id')->constrained()->onDelete('cascade');
            $table->string('fichier_pdf'); // chemin du PDF
            $table->string('hash')->unique(); // pour vérifier l'intégrité
            $table->string('signature')->nullable(); // signature numérique
            $table->string('cachet')->nullable(); // cachet officiel
            $table->enum('statut', ['brouillon', 'valide', 'archive'])->default('brouillon');
            $table->datetime('date_generation');
            $table->datetime('date_expiration')->nullable(); // validité du document
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carnets');
    }
};
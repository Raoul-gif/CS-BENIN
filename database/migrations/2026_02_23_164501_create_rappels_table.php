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
            $table->date('date_envoi')->nullable();
            $table->boolean('envoye')->default(false);
            $table->string('type')->default('email');
            $table->text('message')->nullable();
            $table->integer('tentatives')->default(0);
            $table->string('email_destinataire')->nullable();
            $table->text('erreur')->nullable();
            $table->timestamps();
            
            $table->index(['envoye', 'date_prevue']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rappels');
    }
};
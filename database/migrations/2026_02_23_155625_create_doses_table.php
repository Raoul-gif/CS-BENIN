<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enfant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vaccin_id')->constrained()->onDelete('cascade');
            $table->date('date_administration');
            $table->string('lieu_administration')->nullable();
            $table->string('lot')->nullable();
            $table->string('administrateur')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Un enfant ne peut pas avoir deux fois le même vaccin
            $table->unique(['enfant_id', 'vaccin_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('doses');
    }
};
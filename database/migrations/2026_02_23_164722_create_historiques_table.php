<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enfant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vaccin_id')->constrained()->onDelete('cascade');
            $table->date('date_administration');
            $table->string('lieu_administration');
            $table->string('professionnel_sante')->nullable();
            $table->string('lot_vaccin')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historiques');
    }
};
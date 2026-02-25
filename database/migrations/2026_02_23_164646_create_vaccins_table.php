<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vaccins', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->integer('age_min_mois');
            $table->integer('age_max_mois')->nullable();
            $table->integer('dose_numero')->nullable();
            $table->string('maladie_previent');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vaccins');
    }
};
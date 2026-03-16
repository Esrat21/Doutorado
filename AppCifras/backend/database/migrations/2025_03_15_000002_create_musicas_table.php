<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('musicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artista_id')->constrained('artistas')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('usuario_criador_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('titulo', 200);
            $table->string('slug', 220);
            $table->string('tom_original', 20)->nullable();
            $table->string('afinacao_padrao', 100)->nullable();
            $table->string('genero', 100)->nullable();
            $table->string('idioma', 50)->nullable();
            $table->timestamps();
            $table->unique(['artista_id', 'slug']);
            $table->index('titulo');
            $table->index('usuario_criador_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('musicas');
    }
};

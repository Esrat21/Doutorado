<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('musica_versoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('musica_id')->constrained('musicas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('usuario_criador_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('numero_versao');
            $table->string('titulo_versao', 200)->nullable();
            $table->longText('conteudo');
            $table->json('estrutura_json')->nullable();
            $table->text('observacoes')->nullable();
            $table->boolean('is_principal')->default(false);
            $table->boolean('is_publica')->default(true);
            $table->timestamps();
            $table->unique(['musica_id', 'numero_versao']);
            $table->index('musica_id');
            $table->index('usuario_criador_id');
            $table->index(['musica_id', 'is_principal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('musica_versoes');
    }
};

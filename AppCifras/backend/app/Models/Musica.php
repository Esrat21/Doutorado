<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Musica extends Model
{
    protected $fillable = [
        'artista_id', 'usuario_criador_id', 'titulo', 'slug',
        'tom_original', 'afinacao_padrao', 'genero', 'idioma',
    ];

    public function artista(): BelongsTo
    {
        return $this->belongsTo(Artista::class);
    }

    public function usuarioCriador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_criador_id');
    }

    public function versoes(): HasMany
    {
        return $this->hasMany(MusicaVersao::class);
    }

    /** Alias para o scope binding das rotas (Laravel pluraliza "versao" como "versaos"). */
    public function versaos(): HasMany
    {
        return $this->versoes();
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_musica')
            ->withPivot('ordem')
            ->withTimestamps();
    }
}

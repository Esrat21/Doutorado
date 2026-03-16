<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MusicaVersao extends Model
{
    protected $table = 'musica_versoes';

    protected $fillable = [
        'musica_id', 'usuario_criador_id', 'numero_versao', 'titulo_versao',
        'conteudo', 'estrutura_json', 'fonte_url', 'observacoes', 'is_principal', 'is_publica',
    ];

    protected function casts(): array
    {
        return [
            'estrutura_json' => 'array',
            'is_principal' => 'boolean',
            'is_publica' => 'boolean',
        ];
    }

    public function musica(): BelongsTo
    {
        return $this->belongsTo(Musica::class);
    }

    public function usuarioCriador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_criador_id');
    }
}

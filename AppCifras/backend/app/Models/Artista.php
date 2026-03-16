<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Artista extends Model
{
    protected $fillable = ['nome', 'slug'];

    public function musicas(): HasMany
    {
        return $this->hasMany(Musica::class);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Musica;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeWebController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()?->id;

        $generos = Musica::when($userId, fn ($q) => $q->where('usuario_criador_id', $userId))
            ->whereNotNull('genero')
            ->where('genero', '!=', '')
            ->distinct()
            ->orderBy('genero')
            ->pluck('genero');
        $defaults = ['Rock', 'Sertanejo', 'Gospel/Religioso', 'MPB', 'Pop', 'Eletrônica'];
        $generos = collect(['Todas'])->merge($generos->isEmpty() ? $defaults : $generos->values());

        $musicas = Musica::with('artista')
            ->when($userId, fn ($q) => $q->where('usuario_criador_id', $userId))
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $artistas = Artista::query()
            ->when($userId, fn ($q) => $q->whereHas('musicas', fn ($m) => $m->where('usuario_criador_id', $userId)))
            ->withCount(['musicas' => fn ($q) => $userId ? $q->where('usuario_criador_id', $userId) : $q])
            ->having('musicas_count', '>', 0)
            ->orderByDesc('musicas_count')
            ->limit(15)
            ->get();

        return view('web.home', [
            'generos' => $generos,
            'musicas' => $musicas,
            'artistas' => $artistas,
        ]);
    }
}

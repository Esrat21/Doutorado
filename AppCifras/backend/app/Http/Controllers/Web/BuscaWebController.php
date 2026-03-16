<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Musica;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuscaWebController extends Controller
{
    public function __invoke(Request $request): View
    {
        $q = $request->get('q', '');
        $q = trim($q);
        $musicas = collect();
        $artistas = collect();

        if ($q !== '') {
            $userId = $request->user()?->id;
            $musicas = Musica::with('artista')
                ->when($userId, fn ($query) => $query->where('usuario_criador_id', $userId))
                ->where(function ($query) use ($q) {
                    $query->where('titulo', 'like', '%' . $q . '%')
                        ->orWhereHas('artista', fn ($a) => $a->where('nome', 'like', '%' . $q . '%'));
                })
                ->orderBy('titulo')
                ->limit(30)
                ->get();

            $artistas = Artista::query()
                ->when($userId, fn ($query) => $query->whereHas('musicas', fn ($m) => $m->where('usuario_criador_id', $userId)))
                ->where('nome', 'like', '%' . $q . '%')
                ->withCount(['musicas' => fn ($mq) => $userId ? $mq->where('usuario_criador_id', $userId) : $mq])
                ->orderBy('nome')
                ->limit(20)
                ->get();
        }

        return view('web.busca', [
            'q' => $q,
            'musicas' => $musicas,
            'artistas' => $artistas,
        ]);
    }
}

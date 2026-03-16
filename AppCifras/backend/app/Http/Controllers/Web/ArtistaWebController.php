<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArtistaWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = Artista::query();
        if ($request->filled('q')) {
            $query->where('nome', 'like', '%' . $request->q . '%');
        }
        $artistas = $query->orderBy('nome')->paginate(20);

        return view('web.artistas.index', ['artistas' => $artistas]);
    }

    public function show(Request $request, Artista $artista): View|RedirectResponse
    {
        $userId = $request->user()?->id;
        $artista->load([
            'musicas' => fn ($q) => $q
                ->when($userId, fn ($q2) => $q2->where('usuario_criador_id', $userId))
                ->orderBy('titulo'),
        ]);

        return view('web.artistas.show', ['artista' => $artista]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['nome' => 'required|string|max:200']);
        $slug = Str::slug($request->nome);
        if (Artista::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . uniqid();
        }
        Artista::create(['nome' => $request->nome, 'slug' => $slug]);
        return redirect()->route('web.artistas.index')->with('success', 'Artista adicionado.');
    }
}

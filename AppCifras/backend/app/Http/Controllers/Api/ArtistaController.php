<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtistaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Artista::query();
        if ($request->filled('q')) {
            $query->where('nome', 'like', '%' . $request->q . '%');
        }
        $artistas = $query->orderBy('nome')->paginate(20);
        return response()->json($artistas);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['nome' => 'required|string|max:200']);
        $slug = Str::slug($request->nome);
        if (Artista::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . uniqid();
        }
        $artista = Artista::create(['nome' => $request->nome, 'slug' => $slug]);
        return response()->json($artista, 201);
    }

    public function show(Request $request, Artista $artista): JsonResponse
    {
        $userId = $request->user()?->id;
        $artista->load([
            'musicas' => fn ($q) => $q
                ->when($userId, fn ($q2) => $q2->where('usuario_criador_id', $userId))
                ->orderBy('titulo'),
        ]);
        return response()->json($artista);
    }
}

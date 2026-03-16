<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlaylistWebController extends Controller
{
    public function index(Request $request): View
    {
        $playlists = $request->user()
            ->playlists()
            ->withCount('musicas')
            ->orderBy('nome')
            ->get();

        return view('web.playlists.index', ['playlists' => $playlists]);
    }

    public function show(Request $request, Playlist $playlist): View|RedirectResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            abort(403);
        }
        $playlist->load(['musicas.artista']);
        $allMusicas = \App\Models\Musica::where('usuario_criador_id', $request->user()->id)
            ->with('artista')
            ->orderBy('titulo')
            ->get();

        return view('web.playlists.show', [
            'playlist' => $playlist,
            'allMusicas' => $allMusicas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|string|max:200',
            'is_public' => 'sometimes|boolean',
        ]);

        $request->user()->playlists()->create([
            'nome' => $request->nome,
            'is_public' => (bool) $request->boolean('is_public', false),
            'slug' => self::generateUniqueSlug($request->user()->id, $request->nome),
        ]);
        return redirect()->route('web.playlists.index')->with('success', 'Playlist criada.');
    }

    public function update(Request $request, Playlist $playlist): RedirectResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            abort(403);
        }
        $request->validate([
            'nome' => 'required|string|max:200',
            'is_public' => 'sometimes|boolean',
        ]);
        $playlist->update([
            'nome' => $request->nome,
            'is_public' => (bool) $request->boolean('is_public', $playlist->is_public),
        ]);
        return redirect()->route('web.playlists.show', $playlist)->with('success', 'Playlist atualizada.');
    }

    public function destroy(Request $request, Playlist $playlist): RedirectResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            abort(403);
        }
        $playlist->delete();
        return redirect()->route('web.playlists.index')->with('success', 'Playlist excluída.');
    }

    public function addMusica(Request $request, Playlist $playlist): RedirectResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            abort(403);
        }
        $request->validate(['musica_id' => 'required|exists:musicas,id']);
        $maxOrdem = (int) DB::table('playlist_musica')->where('playlist_id', $playlist->id)->max('ordem');
        $playlist->musicas()->syncWithoutDetaching([
            $request->musica_id => ['ordem' => $maxOrdem + 1],
        ]);
        return redirect()->route('web.playlists.show', $playlist)->with('success', 'Música adicionada.');
    }

    public function removeMusica(Request $request, Playlist $playlist, int $musica_id): RedirectResponse
    {
        if ($playlist->user_id !== $request->user()->id) {
            abort(403);
        }
        $playlist->musicas()->detach($musica_id);
        return redirect()->route('web.playlists.show', $playlist)->with('success', 'Música removida.');
    }

    private static function generateUniqueSlug(int $userId, string $nome): string
    {
        $base = substr(Str::slug($nome . '-' . $userId), 0, 40);
        $suffix = substr(hash('sha256', $userId . '|' . $nome . '|' . microtime(true)), 0, 10);
        return $base !== '' ? ($base . '-' . $suffix) : $suffix;
    }
}

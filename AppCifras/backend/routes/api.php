<?php

use App\Http\Controllers\Api\ArtistaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CifraClubImportController;
use App\Http\Controllers\Api\CifraController;
use App\Http\Controllers\Api\CifraPdfController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MusicaController;
use App\Http\Controllers\Api\MusicaVersaoController;
use App\Http\Controllers\Api\DetectKeyController;
use App\Http\Controllers\Api\PlaylistController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Rotas públicas (não exigem autenticação)
Route::get('home/generos', [HomeController::class, 'generos']);
Route::get('home/musicas-em-alta', [HomeController::class, 'musicasEmAlta']);
Route::get('home/artistas-populares', [HomeController::class, 'artistasPopulares']);

Route::get('cifra/tons', [CifraController::class, 'tons']);
Route::post('cifra/transpose', [CifraController::class, 'transpose']);
Route::post('cifra/transpose-estrutura', [CifraController::class, 'transposeEstrutura']);

Route::get('artistas', [ArtistaController::class, 'index']);
Route::get('artistas/{artista}', [ArtistaController::class, 'show']);

Route::get('musicas', [MusicaController::class, 'index']);
Route::get('musicas/{musica:slug}', [MusicaController::class, 'show']);

Route::get('musicas/{musica:slug}/versoes', [MusicaVersaoController::class, 'index']);
Route::get('musicas/{musica:slug}/versoes/{versao}', [MusicaVersaoController::class, 'show'])
    ->scopeBindings();
Route::get('musicas/{musica:slug}/versoes/{versao}/pdf', CifraPdfController::class)
    ->scopeBindings();

Route::get('playlists/public/{slug}', [PlaylistController::class, 'showPublic']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::post('musicas/detect-key', DetectKeyController::class);
    Route::post('musicas/importar-cifraclub', CifraClubImportController::class);
    Route::post('musicas/importar-cifraclub-artista', [CifraClubImportController::class, 'importArtista']);
    Route::apiResource('artistas', ArtistaController::class)->only(['store']);
    Route::apiResource('musicas', MusicaController::class)->except(['index', 'show']);

    Route::apiResource('playlists', PlaylistController::class)->except(['index'])->parameters(['playlists' => 'playlist']);
    Route::get('playlists', [PlaylistController::class, 'index']);
    Route::post('playlists/{playlist}/musicas', [PlaylistController::class, 'addMusica']);
    Route::delete('playlists/{playlist}/musicas/{musica_id}', [PlaylistController::class, 'removeMusica']);
    Route::post('musicas/{musica:slug}/versoes', [MusicaVersaoController::class, 'store']);
    Route::put('musicas/{musica:slug}/versoes/{versao}', [MusicaVersaoController::class, 'update'])
        ->scopeBindings();
});

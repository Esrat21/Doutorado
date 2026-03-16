<?php

use App\Http\Controllers\Web\ArtistaWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\BuscaWebController;
use App\Http\Controllers\Web\CifraPdfWebController;
use App\Http\Controllers\Web\HomeWebController;
use App\Http\Controllers\Web\MusicaWebController;
use App\Http\Controllers\Web\PlaylistWebController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthWebController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthWebController::class, 'login'])->name('web.login.submit');
Route::post('/register', [AuthWebController::class, 'register'])->name('web.register.submit');
Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', [HomeWebController::class, 'index'])->name('web.home');
Route::get('/busca', BuscaWebController::class)->name('web.busca');

Route::get('/artistas', [ArtistaWebController::class, 'index'])->name('web.artistas.index');
Route::post('/artistas', [ArtistaWebController::class, 'store'])->name('web.artistas.store')->middleware('auth');
Route::get('/artistas/{artista}', [ArtistaWebController::class, 'show'])->name('web.artistas.show');

Route::get('/musicas', [MusicaWebController::class, 'index'])->name('web.musicas.index');
Route::get('/musicas/nova', [MusicaWebController::class, 'create'])->name('web.musicas.create')->middleware('auth');
Route::post('/musicas', [MusicaWebController::class, 'store'])->name('web.musicas.store')->middleware('auth');
Route::get('/musicas/importar-cifraclub', [MusicaWebController::class, 'importCifraClub'])->name('web.musicas.import-cifraclub')->middleware('auth');
Route::post('/musicas/importar-cifraclub', [MusicaWebController::class, 'submitImportCifraClub'])->name('web.musicas.import-cifraclub.submit')->middleware('auth');
Route::post('/musicas/importar-cifraclub-artista', [MusicaWebController::class, 'submitImportArtista'])->name('web.musicas.import-cifraclub-artista')->middleware('auth');
Route::get('/musicas/{musica:slug}', [MusicaWebController::class, 'show'])->name('web.musicas.show');
Route::get('/musicas/{musica:slug}/versoes/{versao}/pdf', CifraPdfWebController::class)->name('web.musicas.pdf')->middleware('auth')->scopeBindings()->whereNumber('versao');
Route::get('/musicas/{slug}/versoes/{versao}', [MusicaWebController::class, 'editVersao'])->name('web.musicas.edit-versao')->middleware('auth')->whereNumber('versao');
Route::put('/musicas/{slug}/versoes/{versao}', [MusicaWebController::class, 'updateVersao'])->name('web.musicas.update-versao')->middleware('auth')->whereNumber('versao');

Route::middleware('auth')->group(function () {
    Route::get('/playlists', [PlaylistWebController::class, 'index'])->name('web.playlists.index');
    Route::post('/playlists', [PlaylistWebController::class, 'store'])->name('web.playlists.store');
    Route::get('/playlists/{playlist}', [PlaylistWebController::class, 'show'])->name('web.playlists.show');
    Route::put('/playlists/{playlist}', [PlaylistWebController::class, 'update'])->name('web.playlists.update');
    Route::delete('/playlists/{playlist}', [PlaylistWebController::class, 'destroy'])->name('web.playlists.destroy'); // HTML form: POST with _method=DELETE
    Route::post('/playlists/{playlist}/musicas', [PlaylistWebController::class, 'addMusica'])->name('web.playlists.add-musica');
    Route::delete('/playlists/{playlist}/musicas/{musica_id}', [PlaylistWebController::class, 'removeMusica'])->name('web.playlists.remove-musica')->whereNumber('musica_id');
});

Route::fallback(function () {
    return redirect()->route('web.home');
});

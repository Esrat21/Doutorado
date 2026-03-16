<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Musica;
use App\Models\MusicaVersao;
use App\Services\CifraClubImporter;
use App\Services\CifraEstruturaHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CifraClubImportController extends Controller
{
    /**
     * Importa uma cifra do Cifra Club pela URL.
     * Cria artista (se não existir), música e primeira versão com conteúdo e estrutura_json.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|string|url',
        ]);

        $url = $request->input('url');

        try {
            $data = CifraClubImporter::fetchFromUrl($url);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $artistaNome = $data['artista'];
        $titulo = $data['titulo'];
        $conteudo = $data['conteudo'];
        $tom = $data['tom'];

        $slugArtista = Str::slug($artistaNome);
        $artista = Artista::firstOrCreate(
            ['slug' => $slugArtista],
            ['nome' => $artistaNome]
        );

        $slugMusica = Str::slug($titulo);
        if (Musica::where('artista_id', $artista->id)->where('slug', $slugMusica)->exists()) {
            $slugMusica = $slugMusica . '-' . uniqid();
        }

        $estrutura = CifraEstruturaHelper::textToEstrutura($conteudo);

        $musica = Musica::create([
            'artista_id' => $artista->id,
            'usuario_criador_id' => $request->user()->id,
            'titulo' => $titulo,
            'slug' => $slugMusica,
            'tom_original' => $tom,
        ]);

        MusicaVersao::create([
            'musica_id' => $musica->id,
            'usuario_criador_id' => $request->user()->id,
            'numero_versao' => 1,
            'titulo_versao' => 'Versão original',
            'conteudo' => $conteudo,
            'estrutura_json' => $estrutura,
            'is_principal' => true,
            'is_publica' => true,
        ]);

        return response()->json([
            'message' => 'Cifra importada com sucesso.',
            'musica' => $musica->load('artista'),
        ], 201);
    }

    /**
     * Importa músicas do artista. Se a URL contiver musicas.html, importa todas as músicas e todas
     * as versões de cada uma (principal, simplificada, etc.), sem duplicar versões já existentes.
     * Caso contrário, importa uma versão por música (comportamento anterior).
     */
    public function importArtista(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|string|url',
        ]);

        $artistUrl = $request->input('url');
        $importarTodasVersoes = str_contains($artistUrl, 'musicas.html');

        try {
            $result = CifraClubImporter::fetchArtistSongUrls($artistUrl);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $artistaNome = $result['artista'];
        $songUrls = $result['urls'];

        if ($importarTodasVersoes) {
            $versionUrls = [];
            foreach ($songUrls as $songUrl) {
                $versionUrls = array_merge(
                    $versionUrls,
                    CifraClubImporter::getVersionUrlsForSong($songUrl)
                );
            }
            $versionUrls = array_values(array_unique($versionUrls));
        } else {
            $versionUrls = $songUrls;
        }

        $slugArtista = Str::slug($artistaNome);
        $artista = Artista::firstOrCreate(
            ['slug' => $slugArtista],
            ['nome' => $artistaNome]
        );

        $importadas = 0;
        $falhas = 0;
        $musicasIds = [];

        foreach ($versionUrls as $url) {
            $fonteUrl = $this->normalizeFonteUrl($url);
            try {
                $data = CifraClubImporter::fetchFromUrl($url);
            } catch (\Throwable $e) {
                Log::warning('Falha ao importar cifra do Cifra Club', [
                    'url' => $url,
                    'error' => $e->getMessage(),
                ]);
                $falhas++;

                continue;
            }

            $titulo = $data['titulo'];
            $conteudo = $data['conteudo'];
            $tom = $data['tom'];
            $slugMusica = Str::slug($titulo);
            $musica = Musica::where('artista_id', $artista->id)->where('slug', $slugMusica)->first();
            if (! $musica) {
                $slugBase = $slugMusica;
                $suffix = 0;
                while (Musica::where('artista_id', $artista->id)->where('slug', $slugMusica)->exists()) {
                    $slugMusica = $slugBase . '-' . (++$suffix);
                }
                $musica = Musica::create([
                    'artista_id' => $artista->id,
                    'usuario_criador_id' => $request->user()->id,
                    'titulo' => $titulo,
                    'slug' => $slugMusica,
                    'tom_original' => $tom,
                ]);
            }

            if ($importarTodasVersoes) {
                if (MusicaVersao::where('musica_id', $musica->id)->where('fonte_url', $fonteUrl)->exists()) {
                    continue;
                }
            } else {
                if (MusicaVersao::where('musica_id', $musica->id)->exists()) {
                    continue;
                }
            }

            $numeroVersao = (int) MusicaVersao::where('musica_id', $musica->id)->max('numero_versao') + 1;
            $tituloVersao = $this->tituloVersaoFromUrl($url, $numeroVersao);

            MusicaVersao::create([
                'musica_id' => $musica->id,
                'usuario_criador_id' => $request->user()->id,
                'numero_versao' => $numeroVersao,
                'titulo_versao' => $tituloVersao,
                'conteudo' => $conteudo,
                'estrutura_json' => CifraEstruturaHelper::textToEstrutura($conteudo),
                'fonte_url' => $importarTodasVersoes ? $fonteUrl : null,
                'is_principal' => $numeroVersao === 1,
                'is_publica' => true,
            ]);

            $importadas++;
            if (! in_array($musica->id, $musicasIds, true)) {
                $musicasIds[] = $musica->id;
            }
        }

        $musicas = Musica::with('artista')->whereIn('id', $musicasIds)->get();

        return response()->json([
            'artista' => $artista->nome,
            'importadas' => $importadas,
            'falhas' => $falhas,
            'total_urls' => count($versionUrls),
            'musicas' => $musicas,
        ], 200);
    }

    private function normalizeFonteUrl(string $url): string
    {
        $url = explode('#', $url)[0];
        $url = explode('?', $url)[0];

        return rtrim($url, '/');
    }

    private function tituloVersaoFromUrl(string $url, int $numeroVersao): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        if (preg_match('#/simplificada(?:\.html)?/?$#i', $path)) {
            return 'Simplificada';
        }
        if (preg_match('#/tabs-baixo(?:\.html)?/?$#i', $path)) {
            return 'Tabs de baixo';
        }
        if (preg_match('#/aula-completa(?:\.html)?/?$#i', $path)) {
            return 'Aula completa';
        }
        if (preg_match('#/aula(?:\.html)?/?$#i', $path)) {
            return 'Aula';
        }

        return $numeroVersao === 1 ? 'Versão original' : "Versão {$numeroVersao}";
    }
}

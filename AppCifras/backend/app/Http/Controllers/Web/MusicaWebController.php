<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Artista;
use App\Models\Musica;
use App\Models\MusicaVersao;
use App\Services\ChordTransposer;
use App\Services\CifraClubImporter;
use App\Services\CifraEstruturaHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MusicaWebController extends Controller
{
    public function index(Request $request): View
    {
        $query = Musica::with('artista');
        if ($request->user()) {
            $query->where('usuario_criador_id', $request->user()->id);
        }
        if ($request->filled('artista_id')) {
            $query->where('artista_id', $request->artista_id);
        }
        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }
        $musicas = $query->orderBy('titulo')->paginate(20);

        return view('web.musicas.index', ['musicas' => $musicas]);
    }

    public function show(Request $request, Musica $musica): View
    {
        $musica->load(['artista', 'versoes']);
        $versoes = $musica->versoes()->orderBy('numero_versao')->get();

        $versaoId = $request->integer('versao', $versoes->first()?->id);
        $versaoAtiva = $versoes->firstWhere('id', $versaoId) ?? $versoes->first();

        $tomOriginal = trim($musica->tom_original ?? 'C');
        $tomSelecionado = $request->get('tom', $tomOriginal);
        $tonsTodos = ChordTransposer::allKeysIncludingMinor();
        if (! in_array($tomSelecionado, $tonsTodos, true)) {
            $tomSelecionado = $tomOriginal;
        }
        $tonsParaSelect = ChordTransposer::tonsParaSelect();
        $tomRelativo = ChordTransposer::tomRelativo($tomSelecionado);

        $conteudoExibir = $versaoAtiva?->conteudo ?? '';
        $estruturaExibir = null;
        $usaEstrutura = false;
        if ($versaoAtiva && is_array($versaoAtiva->estrutura_json) && ! empty($versaoAtiva->estrutura_json['secoes'] ?? [])) {
            $usaEstrutura = true;
            $estruturaExibir = $versaoAtiva->estrutura_json;
        }

        $semitones = ChordTransposer::semitonesBetween($tomOriginal, $tomSelecionado);
        if ($semitones !== 0) {
            if ($usaEstrutura && $estruturaExibir) {
                $estruturaExibir = ChordTransposer::transposeEstrutura($estruturaExibir, $semitones);
            } else {
                $conteudoExibir = ChordTransposer::transpose($conteudoExibir, $semitones);
            }
        }

        $playlists = collect();
        if ($request->user()) {
            $playlists = $request->user()->playlists()->orderBy('nome')->get();
        }

        $capoCasa = (int) $request->get('capo_casa', 0);
        if ($capoCasa < 0 || $capoCasa > 12) {
            $capoCasa = 0;
        }
        $capoAtivo = $capoCasa > 0;
        $conteudoComCapo = null;
        $conteudoTomReal = null;
        if ($capoAtivo && $versaoAtiva) {
            $semitonesCapo = $capoCasa;
            if ($usaEstrutura && $estruturaExibir) {
                $conteudoComCapo = $estruturaExibir; // já é o tom exibido (pode ser transposto)
                $conteudoTomReal = ChordTransposer::transposeEstrutura($estruturaExibir, $semitonesCapo);
            } else {
                $conteudoComCapo = $conteudoExibir;
                $conteudoTomReal = ChordTransposer::transpose($conteudoExibir, $semitonesCapo);
            }
        }

        return view('web.musicas.show', [
            'musica' => $musica,
            'versoes' => $versoes,
            'versao_ativa' => $versaoAtiva,
            'conteudo_exibir' => $conteudoExibir,
            'estrutura_exibir' => $estruturaExibir,
            'usa_estrutura' => $usaEstrutura,
            'tom_original' => $tomOriginal,
            'tom_selecionado' => $tomSelecionado,
            'tons_para_select' => $tonsParaSelect,
            'tom_relativo' => $tomRelativo,
            'playlists' => $playlists,
            'capo_ativo' => $capoAtivo,
            'capo_casa' => $capoCasa,
            'conteudo_com_capo' => $conteudoComCapo,
            'conteudo_tom_real' => $conteudoTomReal,
        ]);
    }

    public function create(): View
    {
        return view('web.musicas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'artista_nome' => 'required|string|max:200',
            'titulo' => 'required|string|max:200',
            'tom_original' => 'nullable|string|max:20',
            'conteudo' => 'nullable|string',
        ]);

        $nome = trim($request->artista_nome);
        $slug = Str::slug($nome);
        $artista = Artista::firstOrCreate(['slug' => $slug], ['nome' => $nome]);

        $slugMusica = Str::slug($request->titulo);
        if (Musica::where('artista_id', $artista->id)->where('slug', $slugMusica)->exists()) {
            $slugMusica = $slugMusica . '-' . uniqid();
        }

        $musica = Musica::create([
            'artista_id' => $artista->id,
            'usuario_criador_id' => $request->user()->id,
            'titulo' => $request->titulo,
            'slug' => $slugMusica,
            'tom_original' => $request->tom_original ?: null,
        ]);

        $conteudo = $request->conteudo ?: '';
        $estrutura = $conteudo ? [
            'tom' => $request->tom_original ?: 'C',
            'capo' => 0,
            'secoes' => [['nome' => 'Versão original', 'linhas' => [['letra' => $conteudo, 'acordes' => []]]]],
        ] : null;
        if (is_array($estrutura) && ! empty($estrutura['secoes'] ?? [])) {
            $conteudo = $conteudo ?: CifraEstruturaHelper::estruturaToText($estrutura);
        }

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

        return redirect()->route('web.musicas.show', $musica->slug)->with('success', 'Música criada.');
    }

    public function importCifraClub(): View
    {
        return view('web.musicas.import-cifraclub');
    }

    public function submitImportCifraClub(Request $request): RedirectResponse
    {
        $request->validate(['url' => 'required|string|url']);
        $url = $request->input('url');
        if (! str_contains($url, 'cifraclub.com.br')) {
            return redirect()->route('web.musicas.import-cifraclub')->with('error', 'A URL deve ser do Cifra Club.');
        }
        try {
            $data = CifraClubImporter::fetchFromUrl($url);
        } catch (\Throwable $e) {
            return redirect()->route('web.musicas.import-cifraclub')->with('error', $e->getMessage());
        }
        $artista = Artista::firstOrCreate(
            ['slug' => Str::slug($data['artista'])],
            ['nome' => $data['artista']]
        );
        $slugMusica = Str::slug($data['titulo']);
        if (Musica::where('artista_id', $artista->id)->where('slug', $slugMusica)->exists()) {
            $slugMusica = $slugMusica . '-' . uniqid();
        }
        $musica = Musica::create([
            'artista_id' => $artista->id,
            'usuario_criador_id' => $request->user()->id,
            'titulo' => $data['titulo'],
            'slug' => $slugMusica,
            'tom_original' => $data['tom'] ?? null,
        ]);
        $estrutura = CifraEstruturaHelper::textToEstrutura($data['conteudo']);
        MusicaVersao::create([
            'musica_id' => $musica->id,
            'usuario_criador_id' => $request->user()->id,
            'numero_versao' => 1,
            'titulo_versao' => 'Versão original',
            'conteudo' => $data['conteudo'],
            'estrutura_json' => $estrutura,
            'is_principal' => true,
            'is_publica' => true,
        ]);
        return redirect()->route('web.musicas.show', $musica->slug)->with('success', 'Cifra importada com sucesso.');
    }

    public function submitImportArtista(Request $request): RedirectResponse
    {
        $request->validate(['url' => 'required|string|url']);
        $artistUrl = $request->input('url');
        if (! str_contains($artistUrl, 'cifraclub.com.br')) {
            return redirect()->route('web.musicas.import-cifraclub')->with('error', 'A URL deve ser do Cifra Club.');
        }
        try {
            $result = CifraClubImporter::fetchArtistSongUrls($artistUrl);
        } catch (\Throwable $e) {
            return redirect()->route('web.musicas.import-cifraclub')->with('error', $e->getMessage());
        }
        $artista = Artista::firstOrCreate(
            ['slug' => Str::slug($result['artista'])],
            ['nome' => $result['artista']]
        );
        $importadas = 0;
        $falhas = 0;
        foreach ($result['urls'] as $url) {
            try {
                $data = CifraClubImporter::fetchFromUrl($url);
            } catch (\Throwable $e) {
                Log::warning('Falha ao importar cifra', ['url' => $url, 'error' => $e->getMessage()]);
                $falhas++;
                continue;
            }
            $slugMusica = Str::slug($data['titulo']);
            $musica = Musica::where('artista_id', $artista->id)->where('slug', $slugMusica)->first();
            if (! $musica) {
                $suffix = '';
                while (Musica::where('artista_id', $artista->id)->where('slug', $slugMusica . $suffix)->exists()) {
                    $suffix = '-' . uniqid();
                }
                $musica = Musica::create([
                    'artista_id' => $artista->id,
                    'usuario_criador_id' => $request->user()->id,
                    'titulo' => $data['titulo'],
                    'slug' => $slugMusica . $suffix,
                    'tom_original' => $data['tom'] ?? null,
                ]);
            }
            if (MusicaVersao::where('musica_id', $musica->id)->exists()) {
                continue;
            }
            MusicaVersao::create([
                'musica_id' => $musica->id,
                'usuario_criador_id' => $request->user()->id,
                'numero_versao' => 1,
                'titulo_versao' => 'Versão original',
                'conteudo' => $data['conteudo'],
                'estrutura_json' => CifraEstruturaHelper::textToEstrutura($data['conteudo']),
                'is_principal' => true,
                'is_publica' => true,
            ]);
            $importadas++;
        }
        return redirect()->route('web.musicas.import-cifraclub')->with('success', "{$importadas} música(s) importada(s) de {$artista->nome}. " . ($falhas ? "{$falhas} falha(s)." : ''));
    }


    public function editVersao(Request $request, string $slug, int $versao): View|RedirectResponse
    {
        $musica = Musica::where('slug', $slug)->firstOrFail();
        $versaoModel = $musica->versoes()->findOrFail($versao);
        if ($versaoModel->usuario_criador_id !== $request->user()->id) {
            abort(403);
        }
        return view('web.musicas.edit-versao', ['musica' => $musica, 'versao' => $versaoModel]);
    }

    public function updateVersao(Request $request, string $slug, int $versao): RedirectResponse
    {
        $musica = Musica::where('slug', $slug)->firstOrFail();
        $versaoModel = $musica->versoes()->findOrFail($versao);
        if ($versaoModel->usuario_criador_id !== $request->user()->id) {
            abort(403);
        }
        $request->validate([
            'titulo_versao' => 'nullable|string|max:200',
            'conteudo' => 'nullable|string',
        ]);
        $versaoModel->update([
            'titulo_versao' => $request->titulo_versao ?: null,
            'conteudo' => $request->conteudo ?: '',
        ]);
        return redirect()->route('web.musicas.show', $musica->slug)->with('success', 'Versão atualizada.');
    }
}

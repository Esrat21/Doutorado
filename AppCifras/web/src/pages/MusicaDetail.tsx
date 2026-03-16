import { useCallback, useEffect, useState } from 'react';
import { Link, useParams, useSearchParams } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { musicas as musicasApi, versoes as versoesApi, cifra as cifraApi, downloadCifraPdf, playlists as playlistsApi, type PlaylistItem, type CifraEstrutura } from '../api/client';
import { Card, Spinner, Alert, Button, Label, Select, Modal, Checkbox } from 'flowbite-react';

const TONS_MAIOR = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
const TONS_MENOR_RELATIVO: Record<string, string> = {
  'C': 'Am', 'C#': 'A#m', 'D': 'Bm', 'D#': 'Cm', 'E': 'C#m', 'F': 'Dm', 'F#': 'D#m',
  'G': 'Em', 'G#': 'Fm', 'A': 'F#m', 'A#': 'Gm', 'B': 'G#m',
};
const TONS_MAIOR_RELATIVO: Record<string, string> = {
  'Am': 'C', 'A#m': 'C#', 'Bm': 'D', 'Cm': 'D#', 'C#m': 'E', 'Dm': 'F', 'D#m': 'F#',
  'Em': 'G', 'Fm': 'G#', 'F#m': 'A', 'Gm': 'A#', 'G#m': 'B',
};
const TONS = [...TONS_MAIOR, ...Object.values(TONS_MENOR_RELATIVO).filter((v, i, a) => a.indexOf(v) === i)];

function keyToIndex(key: string): number {
  const root = key.replace(/m$|maj$|min$/i, '').trim().replace('q', '#');
  const i = TONS_MAIOR.indexOf(root);
  return i >= 0 ? i : 0;
}

function semitonesBetween(fromKey: string, toKey: string): number {
  const from = keyToIndex(fromKey);
  const to = keyToIndex(toKey);
  return ((to - from) % 12 + 12) % 12;
}

function getTomRelativo(tom: string): string {
  const t = tom.trim();
  if (TONS_MENOR_RELATIVO[t]) return TONS_MENOR_RELATIVO[t];
  const minorKey = t.replace(/m$/i, '');
  const major = TONS_MAIOR_RELATIVO[t as keyof typeof TONS_MAIOR_RELATIVO] ?? TONS_MAIOR_RELATIVO[minorKey + 'm'];
  return major ?? t;
}

type Versao = { id: number; numero_versao: number; titulo_versao?: string; conteudo: string; estrutura_json?: CifraEstrutura | null };

export default function MusicaDetail() {
  const { token } = useAuth();
  const { slug } = useParams<{ slug: string }>();
  const [searchParams, setSearchParams] = useSearchParams();
  const tomFromUrl = searchParams.get('tom') ?? '';
  const [musica, setMusica] = useState<{ id: number; slug: string; titulo: string; artista: { nome: string }; tom_original?: string } | null>(null);
  const [versoes, setVersoes] = useState<Versao[]>([]);
  const [loading, setLoading] = useState(true);
  const [tomSelecionado, setTomSelecionado] = useState('');
  const [versaoAtiva, setVersaoAtiva] = useState<Versao | null>(null);
  const [conteudoTransposto, setConteudoTransposto] = useState('');
  const [cifraTransposta, setCifraTransposta] = useState<CifraEstrutura | null>(null);
  const [capoAtivo, setCapoAtivo] = useState(false);
  const [capoCasa, setCapoCasa] = useState(1);
  const [loadingTranspose, setLoadingTranspose] = useState(false);
  const [exportingPdf, setExportingPdf] = useState(false);
  const [fontSize, setFontSize] = useState(14);
  const [showPlaylistModal, setShowPlaylistModal] = useState(false);
  const [playlists, setPlaylists] = useState<PlaylistItem[]>([]);
  const [loadingPlaylists, setLoadingPlaylists] = useState(false);
  const [playlistSelecionada, setPlaylistSelecionada] = useState<number | ''>('');
  const [novoNomePlaylist, setNovoNomePlaylist] = useState('');
  const [novaPublica, setNovaPublica] = useState(false);
  const [addingToPlaylist, setAddingToPlaylist] = useState(false);

  const tomOriginal = (musica?.tom_original ?? 'C').trim();
  const conteudoBase = versaoAtiva?.conteudo ?? '';
  const cifraBase = versaoAtiva?.estrutura_json ?? null;
  const usaEstrutura = cifraBase != null && Array.isArray(cifraBase.secoes) && cifraBase.secoes.length > 0;

  const atualizarTransposicao = useCallback(async () => {
    const tom = tomSelecionado || tomOriginal;
    const semitones = semitonesBetween(tomOriginal, tom);
    if (semitones === 0) {
      if (usaEstrutura && cifraBase) setCifraTransposta(cifraBase);
      else setConteudoTransposto(conteudoBase);
      return;
    }
    if (usaEstrutura && cifraBase) {
      setLoadingTranspose(true);
      try {
        const res = await cifraApi.transposeEstrutura(cifraBase, semitones);
        setCifraTransposta(res.estrutura);
      } catch {
        setCifraTransposta(cifraBase);
      } finally {
        setLoadingTranspose(false);
      }
    } else if (conteudoBase) {
      setLoadingTranspose(true);
      try {
        const res = await cifraApi.transpose(conteudoBase, semitones);
        setConteudoTransposto(res.conteudo);
      } catch {
        setConteudoTransposto(conteudoBase);
      } finally {
        setLoadingTranspose(false);
      }
    }
  }, [conteudoBase, cifraBase, usaEstrutura, tomOriginal, tomSelecionado]);

  useEffect(() => {
    if (usaEstrutura && cifraBase) {
      const tom = tomSelecionado || tomOriginal;
      const semitones = semitonesBetween(tomOriginal, tom);
      if (semitones === 0) setCifraTransposta(cifraBase);
      else atualizarTransposicao();
    } else {
      if (!conteudoBase) setConteudoTransposto('');
      else if (!tomSelecionado || tomSelecionado === tomOriginal) setConteudoTransposto(conteudoBase);
      else atualizarTransposicao();
    }
  }, [conteudoBase, cifraBase, usaEstrutura, tomOriginal, tomSelecionado, atualizarTransposicao]);

  useEffect(() => {
    if (!slug) return;
    musicasApi
      .get(slug)
      .then((m) => {
        setMusica(m);
        const tomOriginal = (m.tom_original ?? 'C').trim();
        const inicial = tomFromUrl && TONS.includes(tomFromUrl) ? tomFromUrl : tomOriginal;
        setTomSelecionado(inicial);
        setSearchParams(inicial ? { tom: inicial } : {}, { replace: true });
        return versoesApi.list(m.slug);
      })
      .then((list) => {
        setVersoes(list);
        setVersaoAtiva(list.length > 0 ? list[0] : null);
      })
      .catch(() => setMusica(null))
      .finally(() => setLoading(false));
  }, [slug]);

  const setTomAndUrl = useCallback((tom: string) => {
    setTomSelecionado(tom);
    setSearchParams(tom ? { tom } : {}, { replace: true });
  }, [setSearchParams]);

  useEffect(() => {
    if (versaoAtiva && versoes.length > 0 && !versoes.find((v) => v.id === versaoAtiva.id)) {
      setVersaoAtiva(versoes[0]);
    }
  }, [versoes, versaoAtiva]);

  const conteudoExibir = tomSelecionado && tomSelecionado !== tomOriginal ? conteudoTransposto : conteudoBase;
  const cifraExibir = (tomSelecionado && tomSelecionado !== tomOriginal ? cifraTransposta : cifraBase) ?? null;

  const handleExportPdf = async () => {
    if (!musica || !versaoAtiva) return;
    const tom = tomSelecionado || tomOriginal;
    const semitones = semitonesBetween(tomOriginal, tom);
    setExportingPdf(true);
    try {
      await downloadCifraPdf(musica.slug, versaoAtiva.id, {
        semitones,
        capo: capoAtivo ? capoCasa : 0,
      });
    } finally {
      setExportingPdf(false);
    }
  };

  const openPlaylistModal = async () => {
    if (!token || !musica) return;
    setShowPlaylistModal(true);
    setLoadingPlaylists(true);
    try {
      const list = await playlistsApi.list();
      setPlaylists(Array.isArray(list) ? list : []);
    } catch {
      setPlaylists([]);
    } finally {
      setLoadingPlaylists(false);
    }
  };

  const handleAddToPlaylist = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!musica) return;
    setAddingToPlaylist(true);
    try {
      let targetPlaylistId: number | null = typeof playlistSelecionada === 'number' ? playlistSelecionada : null;
      if (!targetPlaylistId && novoNomePlaylist.trim()) {
        const created = await playlistsApi.create(novoNomePlaylist.trim(), novaPublica);
        setPlaylists((prev) => [...prev, created]);
        targetPlaylistId = created.id;
      }
      if (!targetPlaylistId) return;
      await playlistsApi.addMusica(targetPlaylistId, musica.id);
      setShowPlaylistModal(false);
      setPlaylistSelecionada('');
      setNovoNomePlaylist('');
      setNovaPublica(false);
    } finally {
      setAddingToPlaylist(false);
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center py-24">
        <Spinner size="xl" color="purple" />
      </div>
    );
  }
  if (!musica) {
    return (
      <Alert color="warning" className="bg-space-800/80 border-space-600/50">
        Música não encontrada.{' '}
        <Link to="/musicas" className="font-semibold underline hover:text-space-100">Voltar às músicas</Link>
      </Alert>
    );
  }

  return (
    <>
      <nav className="mb-6 flex items-center gap-2 text-sm text-space-400 font-exo">
        <Link to="/" className="hover:text-space-100 transition-colors">Início</Link>
        <span aria-hidden className="text-space-600">/</span>
        <Link to="/musicas" className="hover:text-space-100 transition-colors">Músicas</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200 truncate max-w-[180px] sm:max-w-none">{musica.titulo}</span>
      </nav>

      <div className="flex flex-col lg:flex-row gap-8">
        {/* Sidebar */}
        <aside className="lg:w-72 shrink-0 space-y-6">
          <Card className="bg-space-800/80 border border-space-600/50 p-4">
            <h3 className="font-orbitron font-semibold text-space-100 mb-3">Tom</h3>
            <Select
              value={tomSelecionado || tomOriginal}
              onChange={(e) => setTomAndUrl(e.target.value)}
              className="bg-space-900 border-space-600 text-space-100 rounded-xl"
            >
              <optgroup label="Maior">
                {TONS_MAIOR.map((t) => (
                  <option key={t} value={t}>{t}</option>
                ))}
              </optgroup>
              <optgroup label="Menor (relativo)">
                {Object.values(TONS_MENOR_RELATIVO).map((t) => (
                  <option key={t} value={t}>{t}</option>
                ))}
              </optgroup>
            </Select>
            <div className="flex items-center gap-2 mt-2">
              <Button
                type="button"
                size="xs"
                color="gray"
                className="bg-space-700 hover:bg-space-600 text-space-200 font-exo rounded-lg"
                onClick={() => setTomAndUrl(getTomRelativo(tomSelecionado || tomOriginal))}
              >
                Tom relativo ({getTomRelativo(tomSelecionado || tomOriginal)})
              </Button>
            </div>
            <p className="text-xs text-space-500 mt-1 font-exo">Tom original: {tomOriginal}</p>
          </Card>

          <Card className="bg-space-800/80 border border-space-600/50 p-4">
            <h3 className="font-orbitron font-semibold text-space-100 mb-1">Versões</h3>
            <p className="text-space-500 text-xs font-exo mb-3">
              {versoes.length === 0
                ? 'Nenhuma versão'
                : `${versoes.length} versão(ões) desta música`}
            </p>
            {versoes.length === 0 ? (
              <p className="text-space-500 text-sm font-exo">Adicione ou importe versões (original, simplificada, etc.).</p>
            ) : (
              <ul className="space-y-1 max-h-64 overflow-y-auto pr-1" role="list">
                {versoes.map((v) => (
                  <li key={v.id}>
                    <button
                      type="button"
                      onClick={() => setVersaoAtiva(v)}
                      className={`w-full text-left px-3 py-2 rounded-lg font-exo text-sm transition-colors ${
                        versaoAtiva?.id === v.id
                          ? 'bg-space-500 text-white'
                          : 'text-space-300 hover:bg-space-700 hover:text-space-100'
                      }`}
                    >
                      {v.titulo_versao || `Versão ${v.numero_versao}`}
                    </button>
                  </li>
                ))}
              </ul>
            )}
          </Card>

          <Card className="bg-space-800/80 border border-space-600/50 p-4">
            <h3 className="font-orbitron font-semibold text-space-100 mb-3">Capotraste</h3>
            <div className="flex items-center gap-2 mb-3">
              <input
                type="checkbox"
                id="capo-toggle"
                checked={capoAtivo}
                onChange={(e) => setCapoAtivo(e.target.checked)}
                className="rounded border-space-600 bg-space-900 text-space-500 focus:ring-space-500"
              />
              <Label htmlFor="capo-toggle" className="font-exo text-space-200 cursor-pointer">Mostrar duas colunas</Label>
            </div>
            <div>
              <Label htmlFor="capo-casa" className="font-exo text-space-300 text-sm">Casa</Label>
              <Select
                id="capo-casa"
                value={String(capoCasa)}
                onChange={(e) => setCapoCasa(Number(e.target.value))}
                className="mt-1 w-full bg-space-900 border-space-600 text-space-100 rounded-lg"
              >
                {[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map((n) => (
                  <option key={n} value={n}>{n}ª casa</option>
                ))}
              </Select>
            </div>
          </Card>

          <Card className="bg-space-800/80 border border-space-600/50 p-4">
            <h3 className="font-orbitron font-semibold text-space-100 mb-3">Tamanho do texto</h3>
            <div className="flex items-center gap-2">
              <button
                type="button"
                onClick={() => setFontSize((s) => Math.max(10, s - 1))}
                className="px-2 py-1 rounded-lg bg-space-900 text-space-200 text-sm font-exo hover:bg-space-700"
              >
                −
              </button>
              <input
                type="number"
                min={10}
                max={32}
                value={fontSize}
                onChange={(e) => {
                  const n = Number(e.target.value);
                  if (Number.isNaN(n)) return;
                  setFontSize(Math.min(32, Math.max(10, n)));
                }}
                className="w-16 rounded-lg bg-space-900 border border-space-600 text-space-100 text-sm px-2 py-1 font-mono text-center"
              />
              <span className="text-space-500 text-xs font-exo">px</span>
              <button
                type="button"
                onClick={() => setFontSize((s) => Math.min(32, s + 1))}
                className="px-2 py-1 rounded-lg bg-space-900 text-space-200 text-sm font-exo hover:bg-space-700"
              >
                +
              </button>
            </div>
          </Card>

          <Card className="bg-space-800/80 border border-space-600/50 p-4">
            <Button
              color="purple"
              className="w-full bg-space-500 hover:bg-space-600 rounded-xl font-exo"
              disabled={!versaoAtiva || exportingPdf}
              onClick={handleExportPdf}
            >
              {exportingPdf ? 'Gerando...' : 'Exportar PDF'}
            </Button>
          </Card>
        </aside>

        {/* Conteúdo principal */}
        <div className="flex-1 min-w-0">
          <header className="mb-6 flex flex-wrap items-start justify-between gap-3">
            <div>
              <h1 className="font-orbitron text-3xl font-bold text-space-100">{musica.titulo}</h1>
              <p className="text-space-400 font-exo mt-2">
                {musica.artista?.nome}
                {tomOriginal && <span className="text-space-500 ml-2">• Tom original: {tomOriginal}</span>}
              </p>
            </div>
            {token && (
              <div className="flex items-center gap-2">
                <Button
                  color="gray"
                  size="sm"
                  onClick={openPlaylistModal}
                  className="rounded-full bg-space-800 border border-space-600 text-space-200 hover:bg-space-700 font-exo text-xs px-3 py-1.5"
                >
                  ⋯ Adicionar à playlist
                </Button>
              </div>
            )}
          </header>

          {!versaoAtiva ? (
            <Alert color="info" className="bg-space-800/80 border-space-600/50">Selecione uma versão na barra lateral.</Alert>
          ) : (
            <>
              <div className="flex flex-wrap items-center gap-3 mb-3">
                <h2 className="font-orbitron text-lg font-semibold text-space-100">
                  {versaoAtiva.titulo_versao || `Versão ${versaoAtiva.numero_versao}`}
                </h2>
                <Link
                  to={`/musicas/${musica.slug ?? musica.id}/versoes/${versaoAtiva.id}/editar`}
                  className="text-sm font-exo text-space-400 hover:text-space-200 underline transition-colors"
                >
                  Editar versão
                </Link>
              </div>
              {loadingTranspose && tomSelecionado !== tomOriginal ? (
                <div className="flex justify-center py-8"><Spinner color="purple" /></div>
              ) : capoAtivo && capoCasa > 0 ? (
                <CapoView
                  conteudoComCapo={conteudoExibir}
                  capoCasa={capoCasa}
                  estructuraComCapo={usaEstrutura ? cifraExibir : null}
                  fontSize={fontSize}
                />
              ) : cifraExibir ? (
                <Card className="bg-space-800/80 border border-space-600/50">
                  <CifraEstruturaView estructura={cifraExibir} fontSize={fontSize} />
                </Card>
              ) : (
                <Card className="bg-space-800/80 border border-space-600/50">
                  <pre
                    className="whitespace-pre-wrap font-mono text-space-200 leading-relaxed overflow-x-auto rounded-lg p-4 columns-2 [column-gap:1.5rem]"
                    style={{ fontSize }}
                  >
                    {conteudoExibir || '(sem conteúdo)'}
                  </pre>
                </Card>
              )}
            </>
          )}
        </div>
      </div>

      {token && (
        <Modal show={showPlaylistModal} onClose={() => setShowPlaylistModal(false)} size="lg">
          <div className="bg-space-900 border-b border-space-700 px-6 py-3 text-space-100 font-orbitron text-sm">
            Adicionar à playlist
          </div>
          <div className="bg-space-900 px-6 py-4">
            <form onSubmit={handleAddToPlaylist} className="space-y-5">
              <div className="space-y-2">
                <Label className="text-space-200 font-exo text-sm">Suas playlists</Label>
                {loadingPlaylists ? (
                  <div className="flex justify-center py-6">
                    <Spinner size="md" color="purple" />
                  </div>
                ) : playlists.length === 0 ? (
                  <p className="text-space-500 text-sm font-exo">
                    Você ainda não tem playlists. Crie uma abaixo.
                  </p>
                ) : (
                  <select
                    value={playlistSelecionada === '' ? '' : playlistSelecionada}
                    onChange={(e) => setPlaylistSelecionada(e.target.value ? Number(e.target.value) : '')}
                    className="w-full rounded-xl border border-space-600 bg-space-900 px-4 py-2.5 text-space-100 focus:border-space-500 focus:ring-2 focus:ring-space-500/50 font-exo text-sm"
                  >
                    <option value="">Selecionar playlist</option>
                    {playlists.map((p) => (
                      <option key={p.id} value={p.id}>
                        {p.nome} {p.is_public ? '— pública' : ''}
                      </option>
                    ))}
                  </select>
                )}
              </div>

              <div className="space-y-3 border-t border-space-800 pt-4">
                <Label className="text-space-200 font-exo text-sm">Ou criar nova playlist</Label>
                <input
                  type="text"
                  value={novoNomePlaylist}
                  onChange={(e) => setNovoNomePlaylist(e.target.value)}
                  placeholder="Nome da nova playlist"
                  className="w-full rounded-xl border border-space-600 bg-space-900 px-4 py-2.5 text-space-100 font-exo text-sm"
                />
                <div className="flex items-center gap-2">
                  <Checkbox
                    id="nova-publica"
                    checked={novaPublica}
                    onChange={(e) => setNovaPublica(e.target.checked)}
                    className="border-space-600 text-space-500 focus:ring-space-500"
                  />
                  <Label htmlFor="nova-publica" className="text-space-300 font-exo text-sm cursor-pointer">
                    Tornar playlist pública (compartilhável por link)
                  </Label>
                </div>
              </div>

              <div className="flex justify-end gap-3 pt-2">
                <Button
                  color="gray"
                  type="button"
                  className="rounded-xl bg-space-800 border border-space-600 text-space-200 hover:bg-space-700 font-exo text-sm"
                  onClick={() => setShowPlaylistModal(false)}
                >
                  Cancelar
                </Button>
                <Button
                  color="purple"
                  type="submit"
                  disabled={addingToPlaylist || (!playlistSelecionada && !novoNomePlaylist.trim())}
                  className="rounded-xl bg-space-500 hover:bg-space-600 font-orbitron text-sm"
                >
                  {addingToPlaylist ? 'Adicionando...' : 'Salvar na playlist'}
                </Button>
              </div>
            </form>
          </div>
        </Modal>
      )}
    </>
  );
}

/** Renderiza a estrutura JSON da cifra (seções, linhas, acordes posicionados). Acordes em destaque; tablatura não usa linha de acordes. */
function CifraEstruturaView({ estructura, fontSize }: { estructura: CifraEstrutura; fontSize: number }) {
  const secoes = estructura.secoes ?? [];
  return (
    <div
      className="rounded-lg p-4 font-mono text-space-200 leading-relaxed columns-2 [column-gap:1.5rem]"
      style={{ fontSize }}
    >
      {secoes.map((sec, i) => (
        <div key={i} className="break-inside-avoid mb-4">
          <div className="font-orbitron font-semibold text-space-400 mb-2">
            [{sec.nome}]{sec.tablatura ? ' (Tablatura)' : ''}
          </div>
          {sec.tablatura ? (
            <pre className="font-mono text-xs text-space-300 whitespace-pre leading-tight mb-0 break-inside-avoid [line-height:1.2]">
              {(sec.linhas ?? [])
                .map((linha) => (linha.letra ?? '').replace(/\r?\n/g, '').trim())
                .filter((s) => s.length > 0)
                .join('\n')}
            </pre>
          ) : (
            (sec.linhas ?? []).map((linha, j) => (
              <div key={j} className="mb-1 font-mono whitespace-pre">
                {renderLinhaCifra(linha, fontSize)}
              </div>
            ))
          )}
        </div>
      ))}
    </div>
  );
}

/** Monta a string da linha de acordes a partir do JSON: pos 0-based = índice onde começa o acorde. */
function buildChordLineString(letra: string, acordes: { nome: string; pos: number }[]): string {
  if (!acordes?.length) return '';
  const baseLen = Math.max(letra.length, 1);
  // calcula o tamanho mínimo necessário para acomodar o último acorde
  let maxEnd = 0;
  for (const a of acordes) {
    const nome = (a.nome ?? '').trim();
    if (!nome) continue;
    const start = Math.max(0, Number(a.pos));
    const end = start + nome.length;
    if (end > maxEnd) maxEnd = end;
  }
  const len = Math.max(baseLen, maxEnd || 1);
  const arr: string[] = Array(len).fill(' ');
  for (const a of acordes) {
    const nome = (a.nome ?? '').trim();
    if (!nome) continue;
    const start = Math.max(0, Number(a.pos));
    for (let i = 0; i < nome.length; i++) {
      const idx = start + i;
      if (idx < arr.length) arr[idx] = nome[i];
    }
  }
  return arr.join('');
}

/** Uma linha de cifra: acorde(s) + letra, com acordes na mesma fonte do texto, apenas cor diferente. */
function renderLinhaCifra(
  linha: { letra: string; acordes?: { nome: string; pos: number }[]; acordes_secundarios?: { nome: string; pos: number }[] },
  baseFontSize: number,
) {
  const hasLetra = !!linha.letra && linha.letra.trim().length > 0;
  const letra = hasLetra ? linha.letra : '';
  const acordes = linha.acordes ?? [];
  const acordesSec = linha.acordes_secundarios ?? [];
  const raw1 = acordes.length ? buildChordLineString(letra || ' ', acordes) : '';
  const raw2 = acordesSec.length ? buildChordLineString(letra || ' ', acordesSec) : '';
  const l1 = raw1;
  const l2 = raw2;
  const chordFontSize = baseFontSize;

  // Caso só de acordes (acorde em cima de acorde, sem letra): mostra todas as linhas como acordes
  if (!hasLetra) {
    const chordLines = [l1, l2].filter((x) => x);
    return (
      <pre className="font-mono whitespace-pre leading-snug m-0" style={{ fontSize: chordFontSize }}>
        <span className="text-space-100 font-semibold">
          {chordLines.join('\n')}
        </span>
      </pre>
    );
  }

  // Caso normal: acordes em cima, letra embaixo
  const lines: string[] = [];
  if (l1) lines.push(l1);
  if (l2) lines.push(l2);
  lines.push(letra);
  return (
    <pre className="font-mono whitespace-pre leading-snug m-0">
      <span
        className="text-space-100 font-semibold"
        style={{ fontSize: chordFontSize }}
      >
        {lines.slice(0, -1).join('\n')}
      </span>
      {lines.length > 1 ? '\n' : ''}
      <span
        className="text-space-200"
        style={{ fontSize: baseFontSize }}
      >
        {lines[lines.length - 1] ?? ''}
      </span>
    </pre>
  );
}

function CapoView({
  conteudoComCapo,
  capoCasa,
  estructuraComCapo,
  fontSize,
}: {
  conteudoComCapo: string;
  capoCasa: number;
  estructuraComCapo?: CifraEstrutura | null;
  fontSize: number;
}) {
  const [tomRealTexto, setTomRealTexto] = useState('');
  const [tomRealEstrutura, setTomRealEstrutura] = useState<CifraEstrutura | null>(null);
  useEffect(() => {
    if (estructuraComCapo?.secoes?.length) {
      cifraApi.transposeEstrutura(estructuraComCapo, capoCasa).then((r) => setTomRealEstrutura(r.estrutura)).catch(() => setTomRealEstrutura(null));
    } else if (conteudoComCapo) {
      cifraApi.transpose(conteudoComCapo, capoCasa).then((r) => setTomRealTexto(r.conteudo)).catch(() => setTomRealTexto(''));
    }
  }, [conteudoComCapo, capoCasa, estructuraComCapo]);
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
      <Card className="bg-space-800/80 border border-space-600/50">
        <h3 className="font-orbitron font-semibold text-space-200 mb-2">Com capotraste (casa {capoCasa})</h3>
        {estructuraComCapo?.secoes?.length ? (
          <CifraEstruturaView estructura={estructuraComCapo} fontSize={fontSize} />
        ) : (
          <pre
            className="whitespace-pre-wrap font-mono text-space-200 leading-relaxed overflow-x-auto rounded-lg p-4 bg-space-900/50"
            style={{ fontSize }}
          >
            {conteudoComCapo || '(sem conteúdo)'}
          </pre>
        )}
      </Card>
      <Card className="bg-space-800/80 border border-space-600/50">
        <h3 className="font-orbitron font-semibold text-space-200 mb-2">Tom real</h3>
        {tomRealEstrutura?.secoes?.length ? (
          <CifraEstruturaView estructura={tomRealEstrutura} fontSize={fontSize} />
        ) : (
          <pre
            className="whitespace-pre-wrap font-mono text-space-200 leading-relaxed overflow-x-auto rounded-lg p-4 bg-space-900/50"
            style={{ fontSize }}
          >
            {tomRealTexto || '(carregando...)'}
          </pre>
        )}
      </Card>
    </div>
  );
}

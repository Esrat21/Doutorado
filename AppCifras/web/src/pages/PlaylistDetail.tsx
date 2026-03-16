import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { playlists as playlistsApi, musicas as musicasApi, type PlaylistDetail as PlaylistDetailType } from '../api/client';
import { Button, Card, Spinner, Alert, Badge, TextInput, Label, ToggleSwitch } from 'flowbite-react';

export default function PlaylistDetail() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const { id } = useParams<{ id: string }>();
  const [playlist, setPlaylist] = useState<PlaylistDetailType | null>(null);
  const [allMusicas, setAllMusicas] = useState<{ id: number; titulo: string; artista: { nome: string } }[]>([]);
  const [loading, setLoading] = useState(true);
  const [adding, setAdding] = useState(false);
  const [musicaIdToAdd, setMusicaIdToAdd] = useState<number | ''>('');
  const [editingMeta, setEditingMeta] = useState(false);
  const [nomeEdit, setNomeEdit] = useState('');
  const [isPublicEdit, setIsPublicEdit] = useState(false);

  useEffect(() => {
    if (!token) {
      navigate('/login');
      return;
    }
    if (!id) return;
    const pid = Number(id);
    playlistsApi
      .get(pid)
      .then((p) => {
        setPlaylist(p);
        setNomeEdit(p.nome);
        setIsPublicEdit(p.is_public);
      })
      .catch(() => setPlaylist(null))
      .finally(() => setLoading(false));
    musicasApi.list().then((r: { data?: typeof allMusicas }) => setAllMusicas(Array.isArray(r) ? r : (r?.data || []))).catch(() => setAllMusicas([]));
  }, [token, navigate, id]);

  const addMusica = async (e: React.FormEvent) => {
    e.preventDefault();
    const mid = Number(musicaIdToAdd);
    if (!id || !mid) return;
    setAdding(true);
    try {
      const updated = await playlistsApi.addMusica(Number(id), mid);
      setPlaylist(updated);
      setMusicaIdToAdd('');
    } finally {
      setAdding(false);
    }
  };

  const saveMeta = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!playlist) return;
    try {
      const updated = await playlistsApi.update(playlist.id, nomeEdit.trim() || playlist.nome, isPublicEdit);
      setPlaylist((prev) => (prev ? { ...prev, nome: updated.nome, is_public: updated.is_public } : prev));
      setEditingMeta(false);
    } catch {}
  };

  const removeMusica = async (musicaId: number) => {
    if (!id) return;
    try {
      const updated = await playlistsApi.removeMusica(Number(id), musicaId);
      setPlaylist(updated);
    } catch {}
  };

  if (loading) {
    return (
      <div className="flex justify-center py-24">
        <Spinner size="xl" color="purple" />
      </div>
    );
  }
  if (!playlist) {
    return (
      <Alert color="warning" className="bg-space-800/80 border-space-600/50">
        Playlist não encontrada.{' '}
        <Link to="/playlists" className="font-semibold underline hover:text-space-100">Voltar</Link>
      </Alert>
    );
  }

  const idsInPlaylist = new Set(playlist.musicas.map((m) => m.id));
  const availableToAdd = allMusicas.filter((m) => !idsInPlaylist.has(m.id));

  return (
    <>
      <nav className="mb-6 flex items-center gap-2 text-sm text-space-400 font-exo">
        <Link to="/" className="hover:text-space-100 transition-colors">Início</Link>
        <span aria-hidden className="text-space-600">/</span>
        <Link to="/playlists" className="hover:text-space-100 transition-colors">Playlists</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200 truncate max-w-[160px] sm:max-w-none">{playlist.nome}</span>
      </nav>

      <div className="flex flex-wrap items-center gap-3 mb-6">
        <h1 className="font-orbitron text-3xl font-bold text-space-100">{playlist.nome}</h1>
        <Badge color={playlist.is_public ? 'purple' : 'dark'} className="px-3 py-1 rounded-full font-exo text-xs">
          {playlist.is_public ? 'Pública' : 'Privada'}
        </Badge>
        <Button
          size="xs"
          color="gray"
          onClick={() => setEditingMeta((v) => !v)}
          className="rounded-lg bg-space-800 border border-space-600 text-space-200 hover:bg-space-700 font-exo"
        >
          {editingMeta ? 'Cancelar' : 'Editar'}
        </Button>
      </div>

      {editingMeta && (
        <Card className="mb-8 bg-space-800/60 border-space-600/50 p-4 sm:p-6">
          <form onSubmit={saveMeta} className="flex flex-wrap gap-4 items-end">
            <div className="flex-1 min-w-[200px] space-y-1.5">
              <Label htmlFor="playlist-nome-edit" className="text-space-200 font-exo text-sm">
                Nome
              </Label>
              <TextInput
                id="playlist-nome-edit"
                value={nomeEdit}
                onChange={(e) => setNomeEdit(e.target.value)}
                className="bg-space-900 border-space-600 text-space-100 rounded-xl"
              />
            </div>
            <div className="flex flex-col gap-1">
              <span className="text-space-200 font-exo text-sm">Visibilidade</span>
              <ToggleSwitch
                checked={isPublicEdit}
                label={isPublicEdit ? 'Pública (compartilhável)' : 'Privada'}
                onChange={setIsPublicEdit}
                className="text-space-100"
              />
            </div>
            {playlist.is_public && (
              <div className="w-full mt-4 space-y-1.5">
                <Label className="text-space-200 font-exo text-sm">Link público</Label>
                <TextInput
                  readOnly
                  value={`${window.location.origin}/playlists/public/${playlist.slug}`}
                  className="bg-space-900 border-space-700 text-space-100 font-mono text-xs"
                  onFocus={(e) => e.target.select()}
                />
              </div>
            )}
            <div className="flex gap-2 mt-2">
              <Button type="submit" color="purple" className="bg-space-500 hover:bg-space-600 rounded-xl font-orbitron text-sm">
                Salvar
              </Button>
            </div>
          </form>
        </Card>
      )}

      <section className="mb-8">
        <h2 className="font-orbitron text-xl font-semibold text-space-100 mb-4">Músicas na playlist</h2>
        {playlist.musicas.length === 0 ? (
          <Alert color="info" className="bg-space-800/80 border-space-600/50 text-space-200">
            Nenhuma música. Adicione abaixo.
          </Alert>
        ) : (
          <ul className="space-y-3">
            {playlist.musicas.map((m) => (
              <li key={m.id}>
                <Card className="bg-space-800/60 border border-space-600/40">
                  <div className="flex flex-wrap items-center gap-4">
                    <Link to={`/musicas/${m.slug ?? m.id}`} className="flex-1 min-w-0 font-exo text-space-100 hover:text-space-200 transition-colors truncate">
                      {m.titulo}
                    </Link>
                    <span className="text-space-500 text-sm font-exo shrink-0">{m.artista?.nome}</span>
                    <Button color="failure" size="xs" onClick={() => removeMusica(m.id)} className="shrink-0 rounded-lg">
                      Remover
                    </Button>
                  </div>
                </Card>
              </li>
            ))}
          </ul>
        )}
      </section>

      <Card className="bg-space-800/60 border-space-600/50 p-4 sm:p-6">
        <h3 className="font-orbitron text-lg font-semibold text-space-100 mb-3">Adicionar música</h3>
        <form onSubmit={addMusica} className="flex flex-wrap gap-3 items-end">
          <div className="flex-1 min-w-[200px]">
            <label htmlFor="musica-add" className="sr-only">Escolha uma música</label>
            <select
              id="musica-add"
              value={musicaIdToAdd === '' ? '' : musicaIdToAdd}
              onChange={(e) => setMusicaIdToAdd(e.target.value ? Number(e.target.value) : '')}
              className="w-full rounded-xl border border-space-600 bg-space-900 px-4 py-2.5 text-space-100 focus:border-space-500 focus:ring-2 focus:ring-space-500/50 font-exo text-sm"
            >
              <option value="">Escolha uma música</option>
              {availableToAdd.map((m) => (
                <option key={m.id} value={m.id}>{m.titulo} — {m.artista?.nome}</option>
              ))}
            </select>
          </div>
          <Button type="submit" color="purple" disabled={adding || !musicaIdToAdd} className="bg-space-500 hover:bg-space-600 rounded-xl shrink-0">
            {adding ? 'Adicionando...' : 'Adicionar'}
          </Button>
        </form>
        {availableToAdd.length === 0 && allMusicas.length > 0 && (
          <p className="text-space-500 text-sm mt-2 font-exo">Todas as suas músicas já estão nesta playlist.</p>
        )}
      </Card>
    </>
  );
}

import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { playlists as playlistsApi, type PlaylistItem } from '../api/client';
import { Button, TextInput, Card, Spinner, Label, ToggleSwitch, Badge } from 'flowbite-react';

export default function Playlists() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const [list, setList] = useState<PlaylistItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [newNome, setNewNome] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [isPublic, setIsPublic] = useState(false);

  useEffect(() => {
    if (!token) {
      navigate('/login');
      return;
    }
    playlistsApi
      .list()
      .then((r) => setList(Array.isArray(r) ? r : []))
      .catch(() => setList([]))
      .finally(() => setLoading(false));
  }, [token, navigate]);

  const add = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newNome.trim()) return;
    setSubmitting(true);
    try {
      const created = await playlistsApi.create(newNome.trim(), isPublic);
      setList((prev) => [...prev, created]);
      setNewNome('');
      setIsPublic(false);
    } finally {
      setSubmitting(false);
    }
  };

  const remove = async (id: number) => {
    if (!window.confirm('Excluir esta playlist?')) return;
    try {
      await playlistsApi.delete(id);
      setList((prev) => prev.filter((p) => p.id !== id));
    } catch {}
  };

  return (
    <>
      <nav className="mb-6 flex items-center gap-2 text-sm text-space-400 font-exo">
        <Link to="/" className="hover:text-space-100 transition-colors">Início</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200">Playlists</span>
      </nav>

      <h1 className="font-orbitron text-3xl font-bold text-space-100 mb-2">Playlists</h1>
      <p className="text-space-400 font-exo text-sm mb-8">Organize suas músicas em listas.</p>

      <Card className="mb-8 bg-space-800/60 border-space-600/50 p-4 sm:p-6">
        <form onSubmit={add} className="flex flex-wrap gap-3 items-end">
          <div className="flex-1 min-w-[200px] space-y-2">
            <Label htmlFor="playlist-nome" className="text-space-200 font-exo text-sm">
              Nome da playlist
            </Label>
            <TextInput
              id="playlist-nome"
              placeholder="Nome da playlist"
              value={newNome}
              onChange={(e) => setNewNome(e.target.value)}
              className="flex-1 bg-space-900 border-space-600 text-space-100 rounded-xl"
            />
          </div>
          <div className="flex items-center gap-3">
            <div className="flex flex-col gap-1">
              <span className="text-space-200 font-exo text-sm">Visibilidade</span>
              <ToggleSwitch
                checked={isPublic}
                label={isPublic ? 'Pública (compartilhável)' : 'Privada'}
                onChange={setIsPublic}
                className="text-space-100"
              />
            </div>
            <Button
              type="submit"
              color="purple"
              disabled={submitting}
              className="bg-space-500 hover:bg-space-600 rounded-xl font-orbitron shrink-0"
            >
              Criar
            </Button>
          </div>
        </form>
      </Card>

      {loading ? (
        <div className="flex justify-center py-16">
          <Spinner size="xl" color="purple" />
        </div>
      ) : list.length === 0 ? (
        <p className="text-space-400 font-exo">Nenhuma playlist. Crie uma acima.</p>
      ) : (
        <ul className="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
          {list.map((p) => (
            <li key={p.id}>
              <Card className="bg-space-800/60 border border-space-600/40 flex flex-col h-full">
                <div className="flex items-center gap-3 flex-1 min-w-0">
                  <div className="flex-1 min-w-0">
                    <Link
                      to={`/playlists/${p.id}`}
                      className="font-exo text-space-100 hover:text-space-200 transition-colors truncate font-medium"
                    >
                      {p.nome}
                    </Link>
                    <div className="mt-1 flex items-center gap-2 text-xs font-exo text-space-500">
                      <Badge color={p.is_public ? 'purple' : 'dark'} className="px-2 py-0.5 rounded-full">
                        {p.is_public ? 'Pública' : 'Privada'}
                      </Badge>
                      <span>{p.musicas_count ?? 0} música(s)</span>
                    </div>
                  </div>
                  <Button color="failure" size="xs" onClick={() => remove(p.id)} className="shrink-0 rounded-lg">
                    Excluir
                  </Button>
                </div>
              </Card>
            </li>
          ))}
        </ul>
      )}
    </>
  );
}

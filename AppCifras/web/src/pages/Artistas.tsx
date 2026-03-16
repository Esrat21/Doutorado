import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { artistas as artistasApi } from '../api/client';
import { Button, TextInput, Card, Spinner } from 'flowbite-react';

export default function Artistas() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const [list, setList] = useState<{ id: number; nome: string; slug: string }[]>([]);
  const [loading, setLoading] = useState(true);
  const [newNome, setNewNome] = useState('');
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    artistasApi
      .list()
      .then((r: { data?: typeof list }) => setList(Array.isArray(r) ? r : (r?.data || [])))
      .catch(() => setList([]))
      .finally(() => setLoading(false));
  }, []);

  const add = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newNome.trim()) return;
    setSubmitting(true);
    try {
      const created = await artistasApi.create(newNome.trim());
      setList((prev) => [...prev, created]);
      setNewNome('');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <>
      <nav className="mb-6 flex items-center gap-2 text-sm text-space-400 font-exo">
        <Link to="/" className="hover:text-space-100 transition-colors">Início</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200">Artistas</span>
      </nav>

      <h1 className="font-orbitron text-3xl font-bold text-space-100 mb-2">Artistas</h1>
      <p className="text-space-400 font-exo text-sm mb-8">Adicione artistas e filtre músicas por eles.</p>

      <Card className="mb-8 bg-space-800/60 border-space-600/50 p-4 sm:p-6">
        <form onSubmit={add} className="flex flex-wrap gap-3">
          <TextInput
            placeholder="Nome do artista"
            value={newNome}
            onChange={(e) => setNewNome(e.target.value)}
            className="flex-1 min-w-[200px] bg-space-900 border-space-600 text-space-100 rounded-xl"
          />
          <Button type="submit" color="purple" disabled={submitting} className="bg-space-500 hover:bg-space-600 rounded-xl font-orbitron shrink-0">
            Adicionar
          </Button>
        </form>
      </Card>

      {loading ? (
        <div className="flex justify-center py-16">
          <Spinner size="xl" color="purple" />
        </div>
      ) : list.length === 0 ? (
        <p className="text-space-400 font-exo">Nenhum artista. Adicione um acima.</p>
      ) : (
        <ul className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
          {list.map((a) => (
            <li key={a.id}>
              <Link to={`/artistas/${a.id}`}>
                <Card className="bg-space-800/60 border border-space-600/40 hover:border-space-500/50 transition-all h-full">
                  <span className="font-exo text-space-100">{a.nome}</span>
                </Card>
              </Link>
            </li>
          ))}
        </ul>
      )}
    </>
  );
}

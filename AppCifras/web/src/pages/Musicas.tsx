import { useEffect, useState } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { musicas as musicasApi } from '../api/client';
import { Button, Card, Spinner } from 'flowbite-react';

export default function Musicas() {
  const [searchParams] = useSearchParams();
  const artistaId = searchParams.get('artista_id');
  const [musicas, setMusicas] = useState<{ id: number; slug: string; titulo: string; artista: { nome: string }; tom_original?: string }[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const params = artistaId ? { artista_id: Number(artistaId) } : undefined;
    musicasApi
      .list(params)
      .then((r: { data?: typeof musicas }) => setMusicas(Array.isArray(r) ? r : (r?.data || [])))
      .catch(() => setMusicas([]))
      .finally(() => setLoading(false));
  }, [artistaId]);

  return (
    <>
      <nav className="mb-6 flex items-center gap-2 text-sm text-space-400 font-exo">
        <Link to="/" className="hover:text-space-100 transition-colors">Início</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200">Músicas</span>
      </nav>

      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
          <h1 className="font-orbitron text-3xl font-bold text-space-100">Músicas</h1>
          <p className="text-space-400 font-exo text-sm mt-1">Gerencie suas cifras</p>
        </div>
        <div className="flex flex-wrap gap-2">
          <Button as={Link} to="/musicas/importar-cifraclub" color="gray" className="bg-space-700 hover:bg-space-600 text-space-200 rounded-xl font-exo shrink-0">
            Importar Cifra Club
          </Button>
          <Button as={Link} to="/musicas/nova" color="purple" className="bg-space-500 hover:bg-space-600 focus:ring-space-500 rounded-xl font-orbitron font-semibold shadow-glow shrink-0">
            + Nova música
          </Button>
        </div>
      </div>

      {loading ? (
        <div className="flex justify-center py-16">
          <Spinner size="xl" color="purple" />
        </div>
      ) : musicas.length === 0 ? (
        <Card className="bg-space-800/80 border-space-600/50 rounded-2xl p-8 text-center">
          <p className="text-space-300 font-exo mb-4">Nenhuma música ainda.</p>
          <Button as={Link} to="/musicas/nova" color="purple" className="bg-space-500 hover:bg-space-600 rounded-xl mx-auto">
            Criar primeira música
          </Button>
        </Card>
      ) : (
        <ul className="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
          {musicas.map((m) => (
            <li key={m.id}>
              <Link to={`/musicas/${m.slug ?? m.id}`}>
                <Card className="bg-space-800/60 border border-space-600/40 hover:border-space-500/60 rounded-xl transition-all hover:shadow-glow h-full">
                  <div className="flex flex-wrap items-start justify-between gap-2">
                    <div className="min-w-0 flex-1">
                      <h2 className="font-orbitron font-semibold text-space-100 text-lg truncate">{m.titulo}</h2>
                      <p className="text-space-400 font-exo text-sm mt-1">{m.artista?.nome}</p>
                    </div>
                    {m.tom_original && (
                      <span className="px-3 py-1 rounded-lg bg-space-600/50 text-space-200 font-orbitron text-sm shrink-0">
                        {m.tom_original}
                      </span>
                    )}
                  </div>
                </Card>
              </Link>
            </li>
          ))}
        </ul>
      )}
    </>
  );
}

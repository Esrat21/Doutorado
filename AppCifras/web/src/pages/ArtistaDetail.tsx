import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { artistas as artistasApi, type ArtistaDetail } from '../api/client';
import { Card, Spinner, Alert } from 'flowbite-react';

export default function ArtistaDetail() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const { id } = useParams<{ id: string }>();
  const [artista, setArtista] = useState<ArtistaDetail | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!token) {
      navigate('/login');
      return;
    }
    if (!id) return;
    const aid = Number(id);
    artistasApi
      .get(aid)
      .then(setArtista)
      .catch(() => setArtista(null))
      .finally(() => setLoading(false));
  }, [token, navigate, id]);

  if (loading) {
    return (
      <div className="flex justify-center py-24">
        <Spinner size="xl" color="purple" />
      </div>
    );
  }
  if (!artista) {
    return (
      <Alert color="warning" className="bg-space-800/80 border-space-600/50">
        Artista não encontrado.{' '}
        <Link to="/artistas" className="font-semibold underline hover:text-space-100">Voltar aos artistas</Link>
      </Alert>
    );
  }

  const musicas = artista.musicas || [];

  return (
    <>
      <nav className="mb-6 flex items-center gap-2 text-sm text-space-400 font-exo">
        <Link to="/" className="hover:text-space-100 transition-colors">Início</Link>
        <span aria-hidden className="text-space-600">/</span>
        <Link to="/artistas" className="hover:text-space-100 transition-colors">Artistas</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200 truncate max-w-[200px] sm:max-w-none">{artista.nome}</span>
      </nav>

      <header className="mb-8 flex flex-wrap items-center gap-4">
        <span className="w-20 h-20 rounded-full bg-space-700 border-2 border-space-600 flex items-center justify-center text-space-200 font-orbitron text-3xl shrink-0">
          {artista.nome.charAt(0).toUpperCase()}
        </span>
        <div>
          <h1 className="font-orbitron text-3xl font-bold text-space-100">{artista.nome}</h1>
          <p className="text-space-400 font-exo mt-1">
            {musicas.length} {musicas.length === 1 ? 'música' : 'músicas'}
          </p>
        </div>
      </header>

      <h2 className="font-orbitron text-xl font-semibold text-space-100 mb-4">Músicas (ordem alfabética)</h2>
      {musicas.length === 0 ? (
        <Alert color="info" className="bg-space-800/80 border-space-600/50 text-space-200">
          Nenhuma música deste artista no seu repertório.{' '}
          <Link to="/musicas/nova" className="font-semibold underline hover:text-space-100">Criar música</Link>
        </Alert>
      ) : (
        <ul className="space-y-2">
          {musicas.map((m) => (
            <li key={m.id}>
              <Link to={`/musicas/${m.slug ?? m.id}`}>
                <Card className="bg-space-800/60 border border-space-600/40 hover:border-space-500/50 transition-all">
                  <div className="flex flex-wrap items-center justify-between gap-2">
                    <span className="font-exo text-space-100 font-medium">{m.titulo}</span>
                    {m.tom_original && (
                      <span className="px-3 py-1 rounded-lg bg-space-600/50 text-space-200 font-orbitron text-sm">
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

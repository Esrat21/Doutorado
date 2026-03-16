import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { home as homeApi } from '../api/client';
import { Spinner } from 'flowbite-react';

type MusicaItem = { id: number; slug: string; titulo: string; artista: { id: number; nome: string }; tom_original?: string };
type ArtistaItem = { id: number; nome: string; slug: string; musicas_count: number };

export default function Home() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const [generos, setGeneros] = useState<string[]>(['Todas']);
  const [generoAtivo, setGeneroAtivo] = useState('Todas');
  const [musicas, setMusicas] = useState<MusicaItem[]>([]);
  const [artistas, setArtistas] = useState<ArtistaItem[]>([]);
  const [loadingGeneros, setLoadingGeneros] = useState(true);
  const [loadingMusicas, setLoadingMusicas] = useState(true);
  const [loadingArtistas, setLoadingArtistas] = useState(true);

  useEffect(() => {
    homeApi
      .generos()
      .then((r) => setGeneros(r.generos || ['Todas']))
      .catch(() => setGeneros(['Todas']))
      .finally(() => setLoadingGeneros(false));
  }, [token, navigate]);

  useEffect(() => {
    setLoadingMusicas(true);
    homeApi
      .musicasEmAlta(generoAtivo === 'Todas' ? undefined : generoAtivo)
      .then((data) => setMusicas(Array.isArray(data) ? data : []))
      .catch(() => setMusicas([]))
      .finally(() => setLoadingMusicas(false));
  }, [token, generoAtivo]);

  useEffect(() => {
    homeApi
      .artistasPopulares()
      .then((data) => setArtistas(Array.isArray(data) ? data : []))
      .catch(() => setArtistas([]))
      .finally(() => setLoadingArtistas(false));
  }, [token]);

  return (
    <>
      {/* Linha de gêneros em alta */}
      <section className="mb-8">
        <h2 className="sr-only">Gêneros</h2>
        {loadingGeneros ? (
          <div className="flex gap-2 overflow-hidden">
            <div className="h-10 w-24 rounded-xl bg-space-800 animate-pulse" />
            <div className="h-10 w-28 rounded-xl bg-space-800 animate-pulse" />
            <div className="h-10 w-32 rounded-xl bg-space-800 animate-pulse" />
          </div>
        ) : (
          <div className="flex gap-2 overflow-x-auto pb-2">
            {generos.map((g) => (
              <button
                key={g}
                type="button"
                onClick={() => setGeneroAtivo(g)}
                className={`shrink-0 rounded-xl px-4 py-2.5 font-exo text-sm font-medium transition-colors ${
                  generoAtivo === g
                    ? 'bg-space-500 text-white'
                    : 'bg-space-800/80 text-space-300 hover:bg-space-700 hover:text-space-100 border border-space-600/50'
                }`}
              >
                {g}
              </button>
            ))}
          </div>
        )}
      </section>

      {/* Músicas em alta - 20 itens em 2 colunas de 10 ou 4 colunas de 5 */}
      <section className="mb-12">
        <div className="flex flex-wrap items-center justify-between gap-4 mb-4">
          <h2 className="font-orbitron text-2xl font-bold text-space-100">Músicas em alta</h2>
          <Link
            to="/musicas"
            className="inline-flex items-center gap-1.5 text-space-400 hover:text-space-100 font-exo text-sm transition-colors"
          >
            Ver mais
            <span aria-hidden className="text-space-500">→</span>
          </Link>
        </div>
        {loadingMusicas ? (
          <div className="flex justify-center py-16">
            <Spinner size="xl" color="purple" />
          </div>
        ) : musicas.length === 0 ? (
          <p className="text-space-400 font-exo py-8">
            Nenhuma música ainda.{' '}
            <Link to="/musicas/nova" className="text-space-400 underline hover:text-space-200">Criar música</Link>
          </p>
        ) : (
          <ul className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-3">
            {musicas.map((m, i) => (
              <li key={m.id}>
                <Link
                  to={`/musicas/${m.slug ?? m.id}`}
                  className="flex items-center gap-3 rounded-xl py-2 -mx-2 px-2 hover:bg-space-800/60 transition-colors group"
                >
                  <span className="flex-shrink-0 w-8 text-right font-orbitron text-sm text-space-500 tabular-nums">
                    {String(i + 1).padStart(2, '0')}
                  </span>
                  <span className="flex-shrink-0 w-10 h-10 rounded-full bg-space-700 flex items-center justify-center text-space-400 font-orbitron text-xs group-hover:bg-space-600">
                    ♪
                  </span>
                  <div className="min-w-0 flex-1">
                    <span className="block font-exo text-space-100 truncate group-hover:text-space-200">{m.titulo}</span>
                    <span className="block font-exo text-sm text-space-500 truncate">{m.artista?.nome}</span>
                  </div>
                </Link>
              </li>
            ))}
          </ul>
        )}
      </section>

      {/* Artistas populares - carrossel horizontal */}
      <section>
        <div className="flex flex-wrap items-center justify-between gap-4 mb-4">
          <h2 className="font-orbitron text-2xl font-bold text-space-100">Artistas populares</h2>
          <Link
            to="/artistas"
            className="inline-flex items-center gap-1.5 text-space-400 hover:text-space-100 font-exo text-sm transition-colors"
          >
            Ver mais
            <span aria-hidden className="text-space-500">→</span>
          </Link>
        </div>
        {loadingArtistas ? (
          <div className="flex gap-4 overflow-hidden">
            {[1, 2, 3, 4, 5].map((i) => (
              <div key={i} className="shrink-0 w-24 flex flex-col items-center gap-2">
                <div className="w-24 h-24 rounded-full bg-space-800 animate-pulse" />
                <div className="h-4 w-20 bg-space-800 rounded animate-pulse" />
              </div>
            ))}
          </div>
        ) : artistas.length === 0 ? (
          <p className="text-space-400 font-exo py-8">
            Nenhum artista ainda.{' '}
            <Link to="/artistas" className="text-space-400 underline hover:text-space-200">Ver artistas</Link>
          </p>
        ) : (
          <div className="overflow-x-auto pb-4 -mx-1">
            <ul className="flex gap-6 min-w-max pr-4">
              {artistas.map((a) => (
                <li key={a.id} className="shrink-0 w-28 flex flex-col items-center">
                  <Link
                    to={`/artistas/${a.id}`}
                    className="flex flex-col items-center gap-2 group"
                  >
                    <span className="w-24 h-24 rounded-full bg-space-700 border-2 border-space-600 flex items-center justify-center text-space-300 font-orbitron text-2xl group-hover:border-space-500 group-hover:bg-space-600 transition-colors">
                      {a.nome.charAt(0).toUpperCase()}
                    </span>
                    <span className="font-exo text-sm text-space-200 text-center leading-tight line-clamp-2 group-hover:text-space-100 transition-colors max-w-full">
                      {a.nome}
                    </span>
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        )}
      </section>
    </>
  );
}

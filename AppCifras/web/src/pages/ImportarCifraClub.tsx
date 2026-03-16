import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { musicas as musicasApi } from '../api/client';
import { Button, Card, Label, TextInput, Alert, Spinner } from 'flowbite-react';

export default function ImportarCifraClub() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const [url, setUrl] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const [artistUrl, setArtistUrl] = useState('');
  const [loadingArtista, setLoadingArtista] = useState(false);
  const [errorArtista, setErrorArtista] = useState('');
  const [resultArtista, setResultArtista] = useState<{
    artista: string;
    importadas: number;
    falhas: number;
    total_urls: number;
    musicas: { slug: string; titulo: string }[];
  } | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setSuccess('');
    const trimmed = url.trim();
    if (!trimmed) {
      setError('Cole a URL da cifra do Cifra Club.');
      return;
    }
    if (!trimmed.includes('cifraclub.com.br')) {
      setError('A URL deve ser do Cifra Club (ex: https://www.cifraclub.com.br/artista/musica/).');
      return;
    }
    setLoading(true);
    try {
      const res = await musicasApi.importFromCifraClub(trimmed);
      setSuccess('Cifra importada com sucesso. Redirecionando para a música...');
      setTimeout(() => navigate(`/musicas/${res.musica.slug}`), 800);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Não foi possível importar. Verifique a URL.');
    } finally {
      setLoading(false);
    }
  };

  const handleSubmitArtista = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorArtista('');
    setResultArtista(null);
    const trimmed = artistUrl.trim();
    if (!trimmed) {
      setErrorArtista('Cole a URL da página do artista no Cifra Club.');
      return;
    }
    if (!trimmed.includes('cifraclub.com.br')) {
      setErrorArtista('A URL deve ser do Cifra Club (ex: https://www.cifraclub.com.br/legiao-urbana/).');
      return;
    }
    setLoadingArtista(true);
    try {
      const res = await musicasApi.importArtistaCifraClub(trimmed);
      setResultArtista({
        artista: res.artista,
        importadas: res.importadas,
        falhas: res.falhas,
        total_urls: res.total_urls,
        musicas: res.musicas.map((m) => ({ slug: m.slug, titulo: m.titulo })),
      });
    } catch (err) {
      setErrorArtista(err instanceof Error ? err.message : 'Não foi possível importar o artista.');
    } finally {
      setLoadingArtista(false);
    }
  };

  if (!token) {
    navigate('/login');
    return null;
  }

  return (
    <>
      <nav className="mb-6 flex items-center gap-2 text-sm text-space-400 font-exo">
        <Link to="/" className="hover:text-space-100 transition-colors">Início</Link>
        <span aria-hidden className="text-space-600">/</span>
        <Link to="/musicas" className="hover:text-space-100 transition-colors">Músicas</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200">Importar do Cifra Club</span>
      </nav>

      <div className="space-y-8">
        <header className="max-w-3xl">
          <h1 className="font-orbitron text-3xl font-bold text-space-100 mb-2">Importar do Cifra Club</h1>
          <p className="text-space-400 font-exo text-sm">
            Cole uma URL de cifra individual ou da página de um artista no Cifra Club. Nós cuidamos de criar o artista, a música e a versão original para você.
          </p>
        </header>

        <div className="grid gap-6 lg:grid-cols-2">
          {/* Importar uma única cifra */}
          <Card className="bg-space-800/80 border border-space-600/50 p-6 flex flex-col h-full">
            <div className="flex items-start justify-between gap-2 mb-4">
              <div>
                <h2 className="font-orbitron text-lg font-semibold text-space-100">Importar uma cifra</h2>
                <p className="text-space-400 font-exo text-xs mt-1">
                  Use para importar uma música específica. Ideal para testar ou trazer poucas cifras.
                </p>
              </div>
            </div>

            <form onSubmit={handleSubmit} className="space-y-4">
              {(error || success) && (
                <Alert
                  color={error ? 'failure' : 'success'}
                  onDismiss={() => {
                    setError('');
                    setSuccess('');
                  }}
                >
                  {error || success}
                </Alert>
              )}
              <div className="space-y-1.5">
                <Label htmlFor="cifraclub-url" className="text-space-200 font-exo text-sm">
                  URL da cifra
                </Label>
                <TextInput
                  id="cifraclub-url"
                  type="url"
                  value={url}
                  onChange={(e) => setUrl(e.target.value)}
                  placeholder="https://www.cifraclub.com.br/legiao-urbana/tempo-perdido/"
                  className="mt-0.5 font-mono text-sm bg-space-900 border-space-600 text-space-100 rounded-xl"
                  disabled={loading || loadingArtista}
                />
                <p className="mt-1 text-xs text-space-500 font-exo">
                  Cole o link completo da música no Cifra Club.
                </p>
              </div>
              <div className="flex flex-wrap gap-3 items-center mt-2">
                <Button
                  type="submit"
                  color="purple"
                  className="bg-space-500 hover:bg-space-400 focus:ring-2 focus:ring-space-400 focus:outline-none font-orbitron rounded-2xl px-5 py-2.5 text-sm uppercase tracking-wide shadow-glow inline-flex items-center gap-2 transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                  disabled={loading || !url.trim()}
                >
                  {loading && <Spinner size="sm" color="purple" />}
                  {loading ? 'Importando...' : 'Importar cifra'}
                </Button>
                <Button
                  as={Link}
                  to="/musicas"
                  color="gray"
                  className="font-exo rounded-2xl border border-space-700 bg-space-900/60 hover:bg-space-800/80 text-space-200 px-4 py-2 text-sm transition-colors"
                >
                  Cancelar
                </Button>
              </div>
            </form>
          </Card>

          {/* Importar todas as músicas de um artista */}
          <Card className="bg-space-800/80 border border-space-600/50 p-6 flex flex-col h-full">
            <div className="flex items-start justify-between gap-2 mb-4">
              <div>
                <h2 className="font-orbitron text-lg font-semibold text-space-100">Importar todas do artista</h2>
                <p className="text-space-400 font-exo text-xs mt-1">
                  Cole a URL da página do artista e deixe o sistema buscar todas as cifras disponíveis para esse artista.
                </p>
              </div>
            </div>

            <form onSubmit={handleSubmitArtista} className="space-y-4">
              {errorArtista && (
                <Alert color="failure" onDismiss={() => setErrorArtista('')}>
                  {errorArtista}
                </Alert>
              )}
              <div className="space-y-1.5">
                <Label htmlFor="artista-url" className="text-space-200 font-exo text-sm">
                  URL do artista
                </Label>
                <TextInput
                  id="artista-url"
                  type="url"
                  value={artistUrl}
                  onChange={(e) => setArtistUrl(e.target.value)}
                  placeholder="https://www.cifraclub.com.br/legiao-urbana/"
                  className="mt-0.5 font-mono text-sm bg-space-900 border-space-600 text-space-100 rounded-xl"
                  disabled={loadingArtista || loading}
                />
                <p className="mt-1 text-xs text-space-500 font-exo">
                  Use a URL principal do artista no Cifra Club (não a de uma música específica).
                </p>
              </div>

              <div className="flex flex-wrap gap-3 items-center mt-2">
                <Button
                  type="submit"
                  color="purple"
                  className="bg-space-500 hover:bg-space-400 focus:ring-2 focus:ring-space-400 focus:outline-none font-orbitron rounded-2xl px-5 py-2.5 text-sm uppercase tracking-wide shadow-glow inline-flex items-center gap-2 transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                  disabled={loadingArtista || !artistUrl.trim()}
                >
                  {loadingArtista && <Spinner size="sm" color="purple" />}
                  {loadingArtista ? 'Importando artista...' : 'Importar músicas do artista'}
                </Button>
                <Button
                  as={Link}
                  to="/musicas"
                  color="gray"
                  className="font-exo rounded-2xl border border-space-700 bg-space-900/60 hover:bg-space-800/80 text-space-200 px-4 py-2 text-sm transition-colors"
                >
                  Voltar
                </Button>
              </div>
            </form>

            {resultArtista && (
              <div className="mt-6 pt-4 border-t border-space-600 text-space-200 font-exo text-sm space-y-2">
                <p className="font-semibold text-space-100">
                  {resultArtista.importadas} música(s) importada(s) de {resultArtista.artista}.
                  {resultArtista.falhas > 0 && ` ${resultArtista.falhas} falha(s).`}
                </p>
                <p className="text-space-500 text-xs">
                  Foram processadas {resultArtista.total_urls} URL(s) de cifra encontradas para esse artista.
                </p>
                {resultArtista.musicas.length > 0 && (
                  <div className="max-h-52 overflow-y-auto rounded-lg bg-space-900/40 border border-space-700/70 px-3 py-2">
                    <ul className="space-y-1">
                      {resultArtista.musicas.slice(0, 20).map((m) => (
                        <li key={m.slug}>
                          <Link
                            to={`/musicas/${m.slug}`}
                            className="text-space-300 hover:text-space-100 underline-offset-2 hover:underline"
                          >
                            {m.titulo}
                          </Link>
                        </li>
                      ))}
                      {resultArtista.musicas.length > 20 && (
                        <li className="text-space-500">
                          … e mais {resultArtista.musicas.length - 20} música(s).
                        </li>
                      )}
                    </ul>
                  </div>
                )}
              </div>
            )}
          </Card>
        </div>
      </div>
    </>
  );
}

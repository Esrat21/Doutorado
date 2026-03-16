import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { musicas as musicasApi, versoes as versoesApi } from '../api/client';
import { Button, Label, TextInput, Textarea, Alert, Spinner, Card } from 'flowbite-react';

export default function EditarVersao() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const { slug, versaoId } = useParams<{ slug: string; versaoId: string }>();
  const [musica, setMusica] = useState<{ id: number; slug: string; titulo: string; artista: { nome: string } } | null>(null);
  const [tituloVersao, setTituloVersao] = useState('');
  const [conteudo, setConteudo] = useState('');
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    if (!token) {
      navigate('/login');
      return;
    }
    if (!slug || !versaoId) return;
    const vid = Number(versaoId);
    if (Number.isNaN(vid) || vid < 1) return;
    setLoading(true);
    setError('');
    musicasApi
      .get(slug)
      .then((m) => {
        setMusica(m);
        return versoesApi.get(slug, vid);
      })
      .then((v) => {
        setTituloVersao(v.titulo_versao ?? '');
        setConteudo(v.conteudo ?? '');
      })
      .catch((err) => {
        setMusica((prev) => (prev ? prev : null));
        setError(err instanceof Error ? err.message : 'Música ou versão não encontrada.');
      })
      .finally(() => setLoading(false));
  }, [token, navigate, slug, versaoId]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!slug || !versaoId) return;
    const vid = Number(versaoId);
    if (Number.isNaN(vid)) return;
    setError('');
    setSubmitting(true);
    try {
      await versoesApi.update(slug, vid, {
        titulo_versao: tituloVersao.trim() || undefined,
        conteudo: conteudo.trim() || undefined,
      });
      navigate(`/musicas/${slug}`);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao salvar.');
    } finally {
      setSubmitting(false);
    }
  };

  if (!token) return null;
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
        Música ou versão não encontrada.{' '}
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
        <Link to={`/musicas/${slug}`} className="hover:text-space-100 transition-colors">{musica.titulo}</Link>
        <span aria-hidden className="text-space-600">/</span>
        <span className="text-space-200">Editar versão</span>
      </nav>

      <Card className="bg-space-800/80 border border-space-600/50 p-6">
        <h1 className="font-orbitron text-xl font-semibold text-space-100 mb-4">Editar versão</h1>
        <form onSubmit={handleSubmit} className="space-y-4">
          {error && (
            <Alert color="failure" onDismiss={() => setError('')}>
              {error}
            </Alert>
          )}
          <div>
            <Label htmlFor="titulo_versao" className="text-space-200 font-exo">Título da versão</Label>
            <TextInput
              id="titulo_versao"
              value={tituloVersao}
              onChange={(e) => setTituloVersao(e.target.value)}
              placeholder="Ex: Versão original, Ao vivo"
              className="mt-1 bg-space-900 border-space-600 text-space-100 font-exo"
            />
          </div>
          <div>
            <Label htmlFor="conteudo" className="text-space-200 font-exo">Cifra (texto)</Label>
            <Textarea
              id="conteudo"
              value={conteudo}
              onChange={(e) => setConteudo(e.target.value)}
              rows={16}
              placeholder={'[Intro]\nAm    C\nLetra da linha...'}
              className="mt-1 bg-space-900 border-space-600 text-space-100 font-mono text-sm"
            />
            <p className="text-xs text-space-500 mt-1 font-exo">
              Use [Nome da seção] para títulos. Linha de acordes acima da letra. O backend gera a estrutura JSON a partir deste texto ao salvar.
            </p>
          </div>
          <div className="flex gap-3 pt-2">
            <Button
              type="submit"
              color="purple"
              className="bg-space-500 hover:bg-space-600 font-exo"
              disabled={submitting}
            >
              {submitting ? 'Salvando...' : 'Salvar'}
            </Button>
            <Button
              type="button"
              color="gray"
              className="font-exo"
              onClick={() => navigate(`/musicas/${slug}`)}
            >
              Cancelar
            </Button>
          </div>
        </form>
      </Card>
    </>
  );
}

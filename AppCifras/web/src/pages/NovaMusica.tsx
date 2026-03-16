import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { musicas as musicasApi, type CifraEstrutura } from '../api/client';
import { Button, Label, TextInput, Textarea, Alert } from 'flowbite-react';

export default function NovaMusica() {
  const { token } = useAuth();
  const navigate = useNavigate();
  const [artistaNome, setArtistaNome] = useState('');
  const [titulo, setTitulo] = useState('');
  const [conteudoCifra, setConteudoCifra] = useState('');
  const [tomOriginal, setTomOriginal] = useState('');
  const [detecting, setDetecting] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');

  const handleDetectKey = async () => {
    if (!conteudoCifra.trim()) {
      setError('Digite ou cole um trecho da cifra para detectar o tom.');
      return;
    }
    setError('');
    setDetecting(true);
    try {
      const res = await musicasApi.detectKey(conteudoCifra);
      setTomOriginal(res.tom ?? '');
    } catch {
      setError('Não foi possível detectar o tom. Tente colar mais acordes.');
    } finally {
      setDetecting(false);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    if (!titulo.trim()) {
      setError('Informe o nome da música.');
      return;
    }
    if (!artistaNome.trim()) {
      setError('Informe o nome do artista.');
      return;
    }
    setSubmitting(true);
    try {
      const conteudo = conteudoCifra.trim();
      const tom = tomOriginal.trim() || undefined;
      const estructura: CifraEstrutura | undefined = conteudo
        ? {
            tom: tom ?? 'C',
            capo: 0,
            secoes: [{ nome: 'Versão original', linhas: [{ letra: conteudo, acordes: [] }] }],
          }
        : undefined;
      const created = await musicasApi.create({
        artista_nome: artistaNome.trim(),
        titulo: titulo.trim(),
        tom_original: tom,
        conteudo: conteudo || undefined,
        estructura,
      });
      navigate(`/musicas/${created.slug}`);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro ao criar música.');
    } finally {
      setSubmitting(false);
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
        <span className="text-space-200">Nova música</span>
      </nav>

      <div className="max-w-2xl">
        <div className="bg-space-800/80 border border-space-600/50 rounded-2xl p-6 sm:p-8 shadow-glow backdrop-blur-sm">
          <h1 className="font-orbitron text-2xl font-bold text-space-100 mb-2">Nova música</h1>
          <p className="text-space-300 text-sm font-exo mb-6">
            Preencha os dados e opcionalmente cole a cifra para detectar o tom automaticamente.
          </p>

          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <Label htmlFor="artista" className="text-space-200 font-exo">Artista</Label>
              <TextInput
                id="artista"
                type="text"
                placeholder="Ex: Legião Urbana"
                value={artistaNome}
                onChange={(e) => setArtistaNome(e.target.value)}
                className="mt-2 bg-space-900 border-space-600 text-space-100 placeholder-space-500 rounded-xl"
              />
            </div>

            <div>
              <Label htmlFor="titulo" className="text-space-200 font-exo">Nome da música</Label>
              <TextInput
                id="titulo"
                type="text"
                placeholder="Ex: Tempo perdido"
                value={titulo}
                onChange={(e) => setTitulo(e.target.value)}
                className="mt-2 bg-space-900 border-space-600 text-space-100 placeholder-space-500 rounded-xl"
              />
            </div>

            <div>
              <Label htmlFor="cifra" className="text-space-200 font-exo">Cifra (opcional — para detectar o tom)</Label>
              <Textarea
                id="cifra"
                placeholder="Cole um trecho da cifra com acordes (ex.: C  G  Am  F) para o tom ser identificado automaticamente."
                value={conteudoCifra}
                onChange={(e) => setConteudoCifra(e.target.value)}
                rows={5}
                className="mt-2 bg-space-900 border-space-600 text-space-100 placeholder-space-500 focus:border-space-500 focus:ring-space-500 rounded-xl font-mono text-sm"
              />
              <div className="mt-2 flex flex-wrap items-center gap-3">
                <Button
                  type="button"
                  color="purple"
                  onClick={handleDetectKey}
                  disabled={detecting || !conteudoCifra.trim()}
                  className="bg-space-500 hover:bg-space-600 rounded-xl"
                >
                  {detecting ? 'Detectando...' : 'Detectar tom'}
                </Button>
                {tomOriginal && (
                  <span className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-space-600/50 border border-space-500 text-space-200 font-orbitron font-semibold">
                    Tom: <span className="text-space-100">{tomOriginal}</span>
                  </span>
                )}
              </div>
            </div>

            <div>
              <Label htmlFor="tom" className="text-space-200 font-exo">Tom original</Label>
              <TextInput
                id="tom"
                type="text"
                placeholder="Ex: C, Am, F#"
                value={tomOriginal}
                onChange={(e) => setTomOriginal(e.target.value)}
                className="mt-2 w-24 bg-space-900 border-space-600 text-space-100 rounded-xl"
              />
              <p className="mt-1 text-xs text-space-400 font-exo">Preenchido ao detectar ou digite manualmente.</p>
            </div>

            {error && (
              <Alert color="failure" className="bg-red-900/20 border-red-500/50 text-red-200">
                {error}
              </Alert>
            )}

            <div className="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
              <Button as={Link} to="/musicas" color="gray" className="rounded-xl font-exo" theme={{ color: { gray: 'bg-space-700 text-space-200 hover:bg-space-600' } }}>
                Cancelar
              </Button>
              <Button
                type="submit"
                color="purple"
                className="bg-space-500 hover:bg-space-600 rounded-xl py-2 font-orbitron font-semibold shadow-glow"
                disabled={submitting}
              >
                {submitting ? 'Criando...' : 'Criar música'}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </>
  );
}

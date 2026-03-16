const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000';

function getToken(): string | null {
  return localStorage.getItem('token');
}

export async function api<T>(
  path: string,
  options: RequestInit = {}
): Promise<T> {
  const token = getToken();
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    ...(options.headers as Record<string, string>),
  };
  if (token) (headers as Record<string, string>)['Authorization'] = `Bearer ${token}`;

  const res = await fetch(`${API_URL}/api${path}`, { ...options, headers });
  const data = await res.json().catch(() => ({}));
  if (!res.ok) throw new Error(data.message || data.error || 'Erro na requisição');
  return data as T;
}

export const auth = {
  async register(name: string, email: string, password: string) {
    return api<{ user: { id: number; name: string; email: string }; token: string }>(
      '/auth/register',
      { method: 'POST', body: JSON.stringify({ name, email, password }) }
    );
  },
  async login(email: string, password: string) {
    return api<{ user: { id: number; name: string; email: string }; token: string }>(
      '/auth/login',
      { method: 'POST', body: JSON.stringify({ email, password }) }
    );
  },
  async logout() {
    await api('/auth/logout', { method: 'POST' });
  },
  async me() {
    return api<{ user: { id: number; name: string; email: string } }>('/auth/me');
  },
};

export const home = {
  generos: () => api<{ generos: string[] }>('/home/generos'),
  musicasEmAlta: (genero?: string) => {
    const q = genero && genero !== 'Todas' ? `?genero=${encodeURIComponent(genero)}` : '';
    return api<{ id: number; slug: string; titulo: string; artista: { id: number; nome: string }; tom_original?: string; genero?: string }[]>(
      '/home/musicas-em-alta' + q
    );
  },
  artistasPopulares: () =>
    api<{ id: number; nome: string; slug: string; musicas_count: number }[]>('/home/artistas-populares'),
};

export type ArtistaDetail = {
  id: number;
  nome: string;
  slug: string;
  musicas: { id: number; slug: string; titulo: string; tom_original?: string }[];
};

export const artistas = {
  list: (params?: { q?: string }) =>
    api<{ data: { id: number; nome: string; slug: string }[] }>(
      '/artistas' + (params?.q ? `?q=${encodeURIComponent(params.q)}` : '')
    ),
  create: (nome: string) =>
    api<{ id: number; nome: string; slug: string }>('/artistas', {
      method: 'POST',
      body: JSON.stringify({ nome }),
    }),
  get: (id: number) => api<ArtistaDetail>(`/artistas/${id}`),
};

/** Estrutura JSON da cifra (tom, capo, secoes com linhas e acordes). */
export type CifraEstrutura = {
  tom?: string;
  capo?: number;
  secoes: {
    nome: string;
    /** Se true, a seção é tablatura (não transpor acordes; afinação não se confunde com cifra). */
    tablatura?: boolean;
    linhas: {
      letra: string;
      acordes: { nome: string; pos: number }[];
      /** Segunda linha de acordes (acordes acima de acordes). */
      acordes_secundarios?: { nome: string; pos: number }[];
    }[];
  }[];
};

export const musicas = {
  list: (params?: { artista_id?: number; q?: string }) => {
    const sp = new URLSearchParams();
    if (params?.artista_id) sp.set('artista_id', String(params.artista_id));
    if (params?.q) sp.set('q', params.q);
    return api<{ data: { id: number; slug: string; titulo: string; artista: { nome: string }; tom_original?: string }[] }>(
      '/musicas' + (sp.toString() ? '?' + sp : '')
    );
  },
  create: (data: {
    artista_id?: number;
    artista_nome?: string;
    titulo: string;
    tom_original?: string;
    conteudo?: string;
    estructura?: CifraEstrutura;
  }) =>
    api<{ id: number; slug: string; titulo: string; artista: { nome: string } }>('/musicas', {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  detectKey: (conteudo: string) =>
    api<{ tom: string | null }>('/musicas/detect-key', {
      method: 'POST',
      body: JSON.stringify({ conteudo }),
    }),
  /** Busca música por id ou slug. */
  get: (idOrSlug: number | string) =>
    api<{ id: number; slug: string; titulo: string; artista: { nome: string }; tom_original?: string; versoes: unknown[] }>(
      `/musicas/${idOrSlug}`
    ),
  /** Importa cifra a partir de uma URL do Cifra Club. Retorna a música criada. */
  importFromCifraClub: (url: string) =>
    api<{ message: string; musica: { id: number; slug: string; titulo: string; artista: { nome: string }; tom_original?: string } }>(
      '/musicas/importar-cifraclub',
      { method: 'POST', body: JSON.stringify({ url }) }
    ),
  /** Importa todas as músicas do artista a partir da URL da página do artista no Cifra Club. */
  importArtistaCifraClub: (url: string) =>
    api<{
      artista: string;
      importadas: number;
      falhas: number;
      total_urls: number;
      musicas: { id: number; slug: string; titulo: string; artista: { nome: string }; tom_original?: string }[];
    }>('/musicas/importar-cifraclub-artista', {
      method: 'POST',
      body: JSON.stringify({ url }),
    }),
};

export const cifra = {
  tons: () => api<{ tons: string[] }>('/cifra/tons'),
  transpose: (conteudo: string, semitones: number) =>
    api<{ conteudo: string }>('/cifra/transpose', {
      method: 'POST',
      body: JSON.stringify({ conteudo, semitones }),
    }),
  transposeEstrutura: (estrutura: CifraEstrutura, semitones: number) =>
    api<{ estrutura: CifraEstrutura }>('/cifra/transpose-estrutura', {
      method: 'POST',
      body: JSON.stringify({ estrutura, semitones }),
    }),
};

export type VersaoPayload = {
  id: number;
  numero_versao: number;
  titulo_versao?: string;
  conteudo: string;
  estrutura_json?: CifraEstrutura | null;
};

export const versoes = {
  /** Lista versões da música (ident = id ou slug). */
  list: (musicaIdent: number | string) =>
    api<VersaoPayload[]>(`/musicas/${musicaIdent}/versoes`),
  /** Obtém uma versão (ident = id ou slug da música). */
  get: (musicaIdent: number | string, versaoId: number) =>
    api<VersaoPayload>(`/musicas/${musicaIdent}/versoes/${versaoId}`),
  create: (musicaIdent: number | string, data: { conteudo?: string; titulo_versao?: string; estrutura?: CifraEstrutura }) =>
    api<VersaoPayload>(`/musicas/${musicaIdent}/versoes`, {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  update: (
    musicaIdent: number | string,
    versaoId: number,
    data: { titulo_versao?: string; conteudo?: string; estrutura?: CifraEstrutura; observacoes?: string; is_principal?: boolean; is_publica?: boolean }
  ) =>
    api<VersaoPayload>(`/musicas/${musicaIdent}/versoes/${versaoId}`, {
      method: 'PUT',
      body: JSON.stringify(data),
    }),
};

/** Baixa o PDF da cifra (musicaIdent = id ou slug). */
export async function downloadCifraPdf(
  musicaIdent: number | string,
  versaoId: number,
  options: { semitones?: number; capo?: number } = {}
): Promise<void> {
  const token = getToken();
  const params = new URLSearchParams();
  if (options.semitones != null) params.set('semitones', String(options.semitones));
  if (options.capo != null) params.set('capo', String(options.capo));
  const url = `${API_URL}/api/musicas/${musicaIdent}/versoes/${versaoId}/pdf${params.toString() ? '?' + params : ''}`;
  const res = await fetch(url, {
    headers: token ? { Authorization: `Bearer ${token}` } : {},
  });
  if (!res.ok) throw new Error('Erro ao gerar PDF');
  const blob = await res.blob();
  const disposition = res.headers.get('Content-Disposition');
  const match = disposition?.match(/filename="?([^";]+)"?/);
  const filename = match ? match[1] : 'cifra.pdf';
  const a = document.createElement('a');
  a.href = URL.createObjectURL(blob);
  a.download = filename;
  a.click();
  URL.revokeObjectURL(a.href);
}

export type PlaylistItem = { id: number; nome: string; slug: string; is_public: boolean; musicas_count?: number };
export type PlaylistDetail = {
  id: number;
  nome: string;
  slug: string;
  is_public: boolean;
  musicas: { id: number; slug: string; titulo: string; artista: { nome: string }; tom_original?: string }[];
};

export const playlists = {
  list: () => api<PlaylistItem[]>('/playlists'),
  create: (nome: string, isPublic = false) =>
    api<PlaylistItem>('/playlists', { method: 'POST', body: JSON.stringify({ nome, is_public: isPublic }) }),
  get: (id: number) => api<PlaylistDetail>(`/playlists/${id}`),
  update: (id: number, nome: string, isPublic: boolean) =>
    api<PlaylistItem>(`/playlists/${id}`, { method: 'PUT', body: JSON.stringify({ nome, is_public: isPublic }) }),
  delete: (id: number) => api<void>(`/playlists/${id}`, { method: 'DELETE' }),
  addMusica: (playlistId: number, musicaId: number) =>
    api<PlaylistDetail>(`/playlists/${playlistId}/musicas`, {
      method: 'POST',
      body: JSON.stringify({ musica_id: musicaId }),
    }),
  removeMusica: (playlistId: number, musicaId: number) =>
    api<PlaylistDetail>(`/playlists/${playlistId}/musicas/${musicaId}`, { method: 'DELETE' }),
};

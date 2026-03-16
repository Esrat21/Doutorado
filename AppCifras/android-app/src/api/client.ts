import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_URL } from '../config/api';

const TOKEN_KEY = '@appcifras/token';

export async function api<T>(path: string, options: RequestInit = {}): Promise<T> {
  const token = await AsyncStorage.getItem(TOKEN_KEY);
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
  get: (id: number) => api<{ id: number; nome: string; slug: string }>(`/artistas/${id}`),
};

export const musicas = {
  list: (params?: { artista_id?: number; q?: string }) => {
    const sp = new URLSearchParams();
    if (params?.artista_id) sp.set('artista_id', String(params.artista_id));
    if (params?.q) sp.set('q', params.q);
    return api<{ data: { id: number; titulo: string; artista: { nome: string }; tom_original?: string }[] }>(
      '/musicas' + (sp.toString() ? '?' + sp : '')
    );
  },
  create: (data: { artista_id: number; titulo: string; tom_original?: string }) =>
    api<{ id: number; titulo: string; artista: { nome: string } }>('/musicas', {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  get: (id: number) =>
    api<{ id: number; titulo: string; artista: { nome: string }; versoes: unknown[] }>(`/musicas/${id}`),
};

export const versoes = {
  list: (musicaId: number) =>
    api<{ id: number; numero_versao: number; titulo_versao?: string; conteudo: string }[]>(
      `/musicas/${musicaId}/versoes`
    ),
  create: (musicaId: number, data: { conteudo: string; titulo_versao?: string }) =>
    api<{ id: number; conteudo: string }>(`/musicas/${musicaId}/versoes`, {
      method: 'POST',
      body: JSON.stringify(data),
    }),
};

export type PlaylistItem = { id: number; nome: string; musicas_count?: number };
export type PlaylistDetail = { id: number; nome: string; musicas: { id: number; titulo: string; artista: { nome: string }; tom_original?: string }[] };

export const playlists = {
  list: () => api<PlaylistItem[]>('/playlists'),
  create: (nome: string) =>
    api<PlaylistItem>('/playlists', { method: 'POST', body: JSON.stringify({ nome }) }),
  get: (id: number) => api<PlaylistDetail>(`/playlists/${id}`),
  update: (id: number, nome: string) =>
    api<PlaylistItem>(`/playlists/${id}`, { method: 'PUT', body: JSON.stringify({ nome }) }),
  delete: (id: number) => api<void>(`/playlists/${id}`, { method: 'DELETE' }),
  addMusica: (playlistId: number, musicaId: number) =>
    api<PlaylistDetail>(`/playlists/${playlistId}/musicas`, {
      method: 'POST',
      body: JSON.stringify({ musica_id: musicaId }),
    }),
  removeMusica: (playlistId: number, musicaId: number) =>
    api<PlaylistDetail>(`/playlists/${playlistId}/musicas/${musicaId}`, { method: 'DELETE' }),
};

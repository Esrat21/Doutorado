import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import Layout from './components/Layout';
import Login from './pages/Login';
import Home from './pages/Home';
import Artistas from './pages/Artistas';
import ArtistaDetail from './pages/ArtistaDetail';
import Musicas from './pages/Musicas';
import NovaMusica from './pages/NovaMusica';
import ImportarCifraClub from './pages/ImportarCifraClub';
import MusicaDetail from './pages/MusicaDetail';
import EditarVersao from './pages/EditarVersao';
import Playlists from './pages/Playlists';
import PlaylistDetail from './pages/PlaylistDetail';

function PrivateRoute({ children }: { children: React.ReactNode }) {
  const { token, loading } = useAuth();
  if (loading) return <div className="flex min-h-screen items-center justify-center"><div className="h-10 w-10 rounded-full border-2 border-space-500 border-t-transparent animate-spin" /></div>;
  return token ? <Layout>{children}</Layout> : <Navigate to="/login" replace />;
}

function PublicLayoutRoute({ children }: { children: React.ReactNode }) {
  return <Layout>{children}</Layout>;
}

function AppRoutes() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      {/* Rotas públicas de navegação */}
      <Route path="/" element={<PublicLayoutRoute><Home /></PublicLayoutRoute>} />
      <Route path="/artistas" element={<PublicLayoutRoute><Artistas /></PublicLayoutRoute>} />
      <Route path="/artistas/:id" element={<PublicLayoutRoute><ArtistaDetail /></PublicLayoutRoute>} />
      <Route path="/musicas" element={<PublicLayoutRoute><Musicas /></PublicLayoutRoute>} />
      <Route path="/musicas/:slug" element={<PublicLayoutRoute><MusicaDetail /></PublicLayoutRoute>} />

      {/* Rotas protegidas para criação/edição */}
      <Route path="/musicas/nova" element={<PrivateRoute><NovaMusica /></PrivateRoute>} />
      <Route path="/musicas/importar-cifraclub" element={<PrivateRoute><ImportarCifraClub /></PrivateRoute>} />
      <Route path="/musicas/:slug/versoes/:versaoId/editar" element={<PrivateRoute><EditarVersao /></PrivateRoute>} />
      <Route path="/playlists" element={<PrivateRoute><Playlists /></PrivateRoute>} />
      <Route path="/playlists/:id" element={<PrivateRoute><PlaylistDetail /></PrivateRoute>} />
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <AppRoutes />
      </BrowserRouter>
    </AuthProvider>
  );
}

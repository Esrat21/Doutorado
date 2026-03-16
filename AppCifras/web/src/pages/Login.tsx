import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Button, Label, TextInput } from 'flowbite-react';

type Tab = 'login' | 'register';

export default function Login() {
  const { login, register } = useAuth();
  const navigate = useNavigate();
  const [tab, setTab] = useState<Tab>('login');
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const submit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      if (tab === 'login') await login(email, password);
      else await register(name, email, password);
      navigate('/');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen w-full bg-space-950 bg-space-gradient bg-nebula-gradient flex items-center justify-center px-4 sm:px-6">
      <div className="w-full max-w-md">
        <h1 className="font-orbitron text-3xl font-bold text-space-100 text-center mb-2">App Cifras</h1>
        <p className="text-space-400 text-center font-exo text-sm mb-8">Entre para continuar</p>

        <div className="flex rounded-xl overflow-hidden border border-space-600/50 bg-space-900/50 p-1 mb-6">
          <button
            type="button"
            onClick={() => { setTab('login'); setError(''); }}
            className={`flex-1 py-3 font-exo font-semibold rounded-lg transition-colors ${tab === 'login' ? 'bg-space-500 text-white' : 'text-space-400 hover:text-space-200'}`}
          >
            Entrar
          </button>
          <button
            type="button"
            onClick={() => { setTab('register'); setError(''); }}
            className={`flex-1 py-3 font-exo font-semibold rounded-lg transition-colors ${tab === 'register' ? 'bg-space-500 text-white' : 'text-space-400 hover:text-space-200'}`}
          >
            Cadastrar
          </button>
        </div>

        <form onSubmit={submit} className="space-y-4 bg-space-800/80 border border-space-600/50 rounded-2xl p-6 shadow-glow">
          {tab === 'register' && (
            <div>
              <Label htmlFor="name" className="text-space-200 font-exo">Nome</Label>
              <TextInput
                id="name"
                type="text"
                placeholder="Seu nome"
                value={name}
                onChange={(e) => setName(e.target.value)}
                required
                className="mt-1 bg-space-900 border-space-600 text-space-100 rounded-xl"
              />
            </div>
          )}
          <div>
            <Label htmlFor="email" className="text-space-200 font-exo">Email</Label>
            <TextInput
              id="email"
              type="email"
              placeholder="seu@email.com"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              className="mt-1 bg-space-900 border-space-600 text-space-100 rounded-xl"
            />
          </div>
          <div>
            <Label htmlFor="password" className="text-space-200 font-exo">Senha</Label>
            <TextInput
              id="password"
              type="password"
              placeholder="••••••••"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              className="mt-1 bg-space-900 border-space-600 text-space-100 rounded-xl"
            />
          </div>
          {error && <p className="text-red-600 text-sm">{error}</p>}
          <Button
            type="submit"
            color="purple"
            className="w-full bg-space-500 hover:bg-space-600 rounded-xl font-orbitron font-semibold"
            disabled={loading}
          >
            {loading ? '...' : tab === 'login' ? 'Entrar' : 'Cadastrar'}
          </Button>
        </form>
      </div>
    </div>
  );
}

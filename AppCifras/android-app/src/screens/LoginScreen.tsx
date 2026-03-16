import React, { useState } from 'react';
import { StyleSheet, Text, TextInput, TouchableOpacity, View } from 'react-native';
import { useAuth } from '../context/AuthContext';

type Tab = 'login' | 'register';

export default function LoginScreen({ onSuccess }: { onSuccess: () => void }) {
  const { login, register } = useAuth();
  const [tab, setTab] = useState<Tab>('login');
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const submit = async () => {
    setError('');
    setLoading(true);
    try {
      if (tab === 'login') await login(email, password);
      else await register(name, email, password);
      onSuccess();
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Erro');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>App Cifras</Text>
      <View style={styles.tabs}>
        <TouchableOpacity style={[styles.tab, tab === 'login' && styles.tabActive]} onPress={() => { setTab('login'); setError(''); }}>
          <Text style={[styles.tabText, tab === 'login' && styles.tabTextActive]}>Entrar</Text>
        </TouchableOpacity>
        <TouchableOpacity style={[styles.tab, tab === 'register' && styles.tabActive]} onPress={() => { setTab('register'); setError(''); }}>
          <Text style={[styles.tabText, tab === 'register' && styles.tabTextActive]}>Cadastrar</Text>
        </TouchableOpacity>
      </View>
      {tab === 'register' && (
        <TextInput style={styles.input} placeholder="Nome" value={name} onChangeText={setName} autoCapitalize="words" />
      )}
      <TextInput style={styles.input} placeholder="Email" value={email} onChangeText={setEmail} keyboardType="email-address" autoCapitalize="none" />
      <TextInput style={styles.input} placeholder="Senha" value={password} onChangeText={setPassword} secureTextEntry />
      {error ? <Text style={styles.error}>{error}</Text> : null}
      <TouchableOpacity style={styles.btn} onPress={submit} disabled={loading}>
        <Text style={styles.btnText}>{loading ? '...' : tab === 'login' ? 'Entrar' : 'Cadastrar'}</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 24, justifyContent: 'center' },
  title: { fontSize: 24, fontWeight: '700', textAlign: 'center', marginBottom: 24 },
  tabs: { flexDirection: 'row', marginBottom: 20 },
  tab: { flex: 1, padding: 12, alignItems: 'center', backgroundColor: '#e0e0e0', marginRight: 4, borderRadius: 8 },
  tabActive: { backgroundColor: '#2563eb' },
  tabText: { fontWeight: '600', color: '#333' },
  tabTextActive: { color: '#fff' },
  input: { borderWidth: 1, borderColor: '#ccc', borderRadius: 8, padding: 12, marginBottom: 12 },
  error: { color: 'red', marginBottom: 12 },
  btn: { backgroundColor: '#2563eb', padding: 14, borderRadius: 8, alignItems: 'center' },
  btnText: { color: '#fff', fontWeight: '600' },
});

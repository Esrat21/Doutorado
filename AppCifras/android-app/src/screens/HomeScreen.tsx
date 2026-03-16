import React, { useEffect, useState } from 'react';
import { ActivityIndicator, FlatList, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useAuth } from '../context/AuthContext';
import { musicas as musicasApi } from '../api/client';

type MusicaItem = { id: number; titulo: string; artista: { nome: string }; tom_original?: string };

export default function HomeScreen() {
  const navigation = useNavigation<any>();
  const { user, token, logout } = useAuth();
  const onLogout = () => logout();
  const onMusica = (id: number) => navigation.navigate('MusicaDetail', { musicaId: id });
  const onArtistas = () => navigation.navigate('Artistas');
  const onMusicas = () => navigation.navigate('Musicas');
  const onPlaylists = () => navigation.navigate('Playlists');
  const [musicas, setMusicas] = useState<MusicaItem[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!token) return;
    musicasApi.list()
      .then((r) => setMusicas(Array.isArray(r) ? r : (r?.data || [])))
      .catch(() => setMusicas([]))
      .finally(() => setLoading(false));
  }, [token]);

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.title}>App Cifras</Text>
        <Text style={styles.email}>{user?.email}</Text>
        <TouchableOpacity onPress={onLogout}><Text style={styles.logout}>Sair</Text></TouchableOpacity>
      </View>
      <View style={styles.nav}>
        <TouchableOpacity onPress={onArtistas}><Text style={styles.navLink}>Artistas</Text></TouchableOpacity>
        <TouchableOpacity onPress={onMusicas}><Text style={styles.navLink}>Músicas</Text></TouchableOpacity>
        <TouchableOpacity onPress={onPlaylists}><Text style={styles.navLink}>Playlists</Text></TouchableOpacity>
      </View>
      <Text style={styles.h2}>Minhas músicas</Text>
      {loading ? (
        <ActivityIndicator size="large" style={styles.loader} />
      ) : musicas.length === 0 ? (
        <Text style={styles.empty}>Nenhuma música. Toque em Músicas para adicionar.</Text>
      ) : (
        <FlatList
          data={musicas}
          keyExtractor={(item) => String(item.id)}
          renderItem={({ item }) => (
            <TouchableOpacity style={styles.item} onPress={() => onMusica(item.id)}>
              <Text style={styles.itemTitle}>{item.titulo}</Text>
              <Text style={styles.itemSub}>{item.artista?.nome} {item.tom_original ? `(${item.tom_original})` : ''}</Text>
            </TouchableOpacity>
          )}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  header: { flexDirection: 'row', alignItems: 'center', marginBottom: 16, gap: 8 },
  title: { fontSize: 20, fontWeight: '700', flex: 1 },
  email: { fontSize: 14, color: '#666' },
  logout: { color: '#2563eb', fontWeight: '600' },
  nav: { flexDirection: 'row', gap: 16, marginBottom: 16 },
  navLink: { color: '#2563eb', fontWeight: '600' },
  h2: { fontSize: 18, fontWeight: '600', marginBottom: 12 },
  loader: { marginTop: 24 },
  empty: { color: '#666' },
  item: { padding: 16, borderBottomWidth: 1, borderBottomColor: '#eee' },
  itemTitle: { fontWeight: '600' },
  itemSub: { fontSize: 14, color: '#666', marginTop: 4 },
});

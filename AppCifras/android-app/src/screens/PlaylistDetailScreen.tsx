import React, { useEffect, useState } from 'react';
import { ActivityIndicator, Alert, FlatList, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import { useRoute, useNavigation } from '@react-navigation/native';
import { playlists as playlistsApi, musicas as musicasApi, type PlaylistDetail as PlaylistDetailType } from '../api/client';

export default function PlaylistDetailScreen() {
  const route = useRoute<any>();
  const navigation = useNavigation<any>();
  const playlistId = route.params?.playlistId ?? 0;
  const [playlist, setPlaylist] = useState<PlaylistDetailType | null>(null);
  const [allMusicas, setAllMusicas] = useState<{ id: number; titulo: string; artista: { nome: string } }[]>([]);
  const [loading, setLoading] = useState(true);
  const [adding, setAdding] = useState(false);
  const [selectedMusicaId, setSelectedMusicaId] = useState<number | ''>('');

  useEffect(() => {
    playlistsApi.get(playlistId).then(setPlaylist).catch(() => setPlaylist(null)).finally(() => setLoading(false));
    musicasApi.list().then((r) => setAllMusicas(Array.isArray(r) ? r : (r?.data || []))).catch(() => setAllMusicas([]));
  }, [playlistId]);

  const addMusica = async () => {
    const mid = Number(selectedMusicaId);
    if (!mid) return;
    setAdding(true);
    try {
      const updated = await playlistsApi.addMusica(playlistId, mid);
      setPlaylist(updated);
      setSelectedMusicaId('');
    } finally {
      setAdding(false);
    }
  };

  const removeMusica = (musicaId: number, titulo: string) => {
    Alert.alert('Remover', `Remover "${titulo}" da playlist?`, [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Remover',
        style: 'destructive',
        onPress: () => playlistsApi.removeMusica(playlistId, musicaId).then(setPlaylist),
      },
    ]);
  };

  if (loading) return <ActivityIndicator style={styles.loader} />;
  if (!playlist) return <Text style={styles.error}>Playlist não encontrada.</Text>;

  const idsInPlaylist = new Set(playlist.musicas.map((m) => m.id));
  const availableToAdd = allMusicas.filter((m) => !idsInPlaylist.has(m.id));

  return (
    <View style={styles.container}>
      <TouchableOpacity onPress={() => navigation.goBack()}><Text style={styles.back}>← Voltar</Text></TouchableOpacity>
      <Text style={styles.title}>{playlist.nome}</Text>
      <Text style={styles.h2}>Músicas na playlist</Text>
      {playlist.musicas.length === 0 ? (
        <Text style={styles.empty}>Nenhuma música. Adicione abaixo.</Text>
      ) : (
        <FlatList
          data={playlist.musicas}
          keyExtractor={(item) => String(item.id)}
          renderItem={({ item }) => (
            <View style={styles.item}>
              <TouchableOpacity style={styles.itemLeft} onPress={() => navigation.navigate('MusicaDetail', { musicaId: item.id })}>
                <Text style={styles.itemTitle}>{item.titulo}</Text>
                <Text style={styles.itemSub}>{item.artista?.nome}</Text>
              </TouchableOpacity>
              <TouchableOpacity onPress={() => removeMusica(item.id, item.titulo)}>
                <Text style={styles.removeBtn}>Remover</Text>
              </TouchableOpacity>
            </View>
          )}
        />
      )}
      <Text style={styles.h3}>Adicionar música</Text>
      <FlatList
        horizontal
        data={availableToAdd}
        keyExtractor={(item) => String(item.id)}
        style={styles.chipList}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={[styles.chip, selectedMusicaId === item.id && styles.chipActive]}
            onPress={() => setSelectedMusicaId(selectedMusicaId === item.id ? '' : item.id)}
          >
            <Text style={[styles.chipText, selectedMusicaId === item.id && styles.chipTextActive]} numberOfLines={1}>
              {item.titulo}
            </Text>
          </TouchableOpacity>
        )}
      />
      {selectedMusicaId ? (
        <TouchableOpacity style={styles.addBtn} onPress={addMusica} disabled={adding}>
          <Text style={styles.addBtnText}>{adding ? '...' : 'Adicionar à playlist'}</Text>
        </TouchableOpacity>
      ) : null}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  loader: { flex: 1, marginTop: 24 },
  error: { padding: 24 },
  back: { color: '#2563eb', marginBottom: 16 },
  title: { fontSize: 20, fontWeight: '700', marginBottom: 16 },
  h2: { fontSize: 18, fontWeight: '600', marginBottom: 12 },
  h3: { fontSize: 16, fontWeight: '600', marginTop: 24, marginBottom: 8 },
  empty: { color: '#666', marginBottom: 16 },
  item: { flexDirection: 'row', alignItems: 'center', padding: 16, borderBottomWidth: 1, borderBottomColor: '#eee' },
  itemLeft: { flex: 1 },
  itemTitle: { fontWeight: '600' },
  itemSub: { fontSize: 14, color: '#666', marginTop: 4 },
  removeBtn: { color: '#c00', fontWeight: '600' },
  chipList: { maxHeight: 44, marginBottom: 12 },
  chip: { paddingHorizontal: 12, paddingVertical: 8, marginRight: 8, backgroundColor: '#e5e7eb', borderRadius: 20 },
  chipActive: { backgroundColor: '#2563eb' },
  chipText: { fontSize: 14 },
  chipTextActive: { color: '#fff' },
  addBtn: { backgroundColor: '#2563eb', padding: 12, borderRadius: 8, alignItems: 'center' },
  addBtnText: { color: '#fff', fontWeight: '600' },
});

import React, { useEffect, useState } from 'react';
import { ActivityIndicator, Alert, FlatList, StyleSheet, Text, TextInput, TouchableOpacity, View } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { playlists as playlistsApi, type PlaylistItem } from '../api/client';

export default function PlaylistsScreen() {
  const navigation = useNavigation<any>();
  const [list, setList] = useState<PlaylistItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [newNome, setNewNome] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const load = () => {
    playlistsApi.list()
      .then((r) => setList(Array.isArray(r) ? r : []))
      .catch(() => setList([]))
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const add = async () => {
    if (!newNome.trim()) return;
    setSubmitting(true);
    try {
      const created = await playlistsApi.create(newNome.trim());
      setList((prev) => [...prev, created]);
      setNewNome('');
    } finally {
      setSubmitting(false);
    }
  };

  const remove = (id: number, nome: string) => {
    Alert.alert('Excluir playlist', `Excluir "${nome}"?`, [
      { text: 'Cancelar', style: 'cancel' },
      {
        text: 'Excluir',
        style: 'destructive',
        onPress: () => playlistsApi.delete(id).then(() => setList((prev) => prev.filter((p) => p.id !== id))),
      },
    ]);
  };

  return (
    <View style={styles.container}>
      <TouchableOpacity onPress={() => navigation.goBack()}><Text style={styles.back}>← Voltar</Text></TouchableOpacity>
      <Text style={styles.title}>Playlists</Text>
      <View style={styles.row}>
        <TextInput style={styles.input} placeholder="Nome da playlist" value={newNome} onChangeText={setNewNome} />
        <TouchableOpacity style={styles.btn} onPress={add} disabled={submitting}>
          <Text style={styles.btnText}>Criar</Text>
        </TouchableOpacity>
      </View>
      {loading ? (
        <ActivityIndicator style={styles.loader} />
      ) : (
        <FlatList
          data={list}
          keyExtractor={(item) => String(item.id)}
          renderItem={({ item }) => (
            <View style={styles.item}>
              <TouchableOpacity style={styles.itemLeft} onPress={() => navigation.navigate('PlaylistDetail', { playlistId: item.id })}>
                <Text style={styles.itemTitle}>{item.nome}</Text>
                <Text style={styles.itemSub}>{item.musicas_count ?? 0} música(s)</Text>
              </TouchableOpacity>
              <TouchableOpacity onPress={() => remove(item.id, item.nome)}>
                <Text style={styles.removeBtn}>Excluir</Text>
              </TouchableOpacity>
            </View>
          )}
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  back: { color: '#2563eb', marginBottom: 16 },
  title: { fontSize: 20, fontWeight: '700', marginBottom: 16 },
  row: { flexDirection: 'row', gap: 8, marginBottom: 16 },
  input: { flex: 1, borderWidth: 1, borderColor: '#ccc', borderRadius: 8, padding: 12 },
  btn: { backgroundColor: '#2563eb', paddingHorizontal: 16, justifyContent: 'center', borderRadius: 8 },
  btnText: { color: '#fff', fontWeight: '600' },
  loader: { marginTop: 24 },
  item: { flexDirection: 'row', alignItems: 'center', padding: 16, borderBottomWidth: 1, borderBottomColor: '#eee' },
  itemLeft: { flex: 1 },
  itemTitle: { fontWeight: '600' },
  itemSub: { fontSize: 14, color: '#666', marginTop: 4 },
  removeBtn: { color: '#c00', fontWeight: '600' },
});

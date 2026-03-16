import React, { useEffect, useState } from 'react';
import { ActivityIndicator, FlatList, StyleSheet, Text, TextInput, TouchableOpacity, View } from 'react-native';
import { artistas as artistasApi, musicas as musicasApi } from '../api/client';

type ArtistaItem = { id: number; nome: string };
type MusicaItem = { id: number; titulo: string; artista: { nome: string }; tom_original?: string };

import { useNavigation } from '@react-navigation/native';

export default function MusicasScreen() {
  const navigation = useNavigation<any>();
  const onBack = () => navigation.goBack();
  const onMusica = (id: number) => navigation.navigate('MusicaDetail', { musicaId: id });
  const [artistas, setArtistas] = useState<ArtistaItem[]>([]);
  const [musicas, setMusicas] = useState<MusicaItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [artistaId, setArtistaId] = useState<number | ''>('');
  const [titulo, setTitulo] = useState('');
  const [tomOriginal, setTomOriginal] = useState('');
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    artistasApi.list().then((r) => setArtistas(Array.isArray(r) ? r : (r?.data || []))).catch(() => setArtistas([]));
  }, []);

  useEffect(() => {
    musicasApi.list(artistaId ? { artista_id: Number(artistaId) } : undefined)
      .then((r) => setMusicas(Array.isArray(r) ? r : (r?.data || [])))
      .catch(() => setMusicas([]))
      .finally(() => setLoading(false));
  }, [artistaId]);

  const add = async () => {
    const aid = Number(artistaId);
    if (!aid || !titulo.trim()) return;
    setSubmitting(true);
    try {
      const created = await musicasApi.create({ artista_id: aid, titulo: titulo.trim(), tom_original: tomOriginal || undefined });
      setMusicas((prev) => [...prev, { ...created, artista: { nome: '' } }]);
      setTitulo('');
      setTomOriginal('');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <View style={styles.container}>
      <TouchableOpacity onPress={onBack}><Text style={styles.back}>← Voltar</Text></TouchableOpacity>
      <Text style={styles.title}>Músicas</Text>
      <View style={styles.form}>
        <Text style={styles.label}>Artista</Text>
        <FlatList
          horizontal
          data={artistas}
          keyExtractor={(item) => String(item.id)}
          renderItem={({ item }) => (
            <TouchableOpacity
              style={[styles.chip, artistaId === item.id && styles.chipActive]}
              onPress={() => setArtistaId(artistaId === item.id ? '' : item.id)}
            >
              <Text style={[styles.chipText, artistaId === item.id && styles.chipTextActive]}>{item.nome}</Text>
            </TouchableOpacity>
          )}
          style={styles.chipList}
        />
        <View style={styles.row}>
          <TextInput style={[styles.input, { flex: 1 }]} placeholder="Título" value={titulo} onChangeText={setTitulo} />
          <TextInput style={[styles.input, { width: 60 }]} placeholder="Tom" value={tomOriginal} onChangeText={setTomOriginal} />
        </View>
        <TouchableOpacity style={styles.btn} onPress={add} disabled={submitting}>
          <Text style={styles.btnText}>Adicionar</Text>
        </TouchableOpacity>
      </View>
      {loading ? <ActivityIndicator style={styles.loader} /> : (
        <FlatList
          data={musicas}
          keyExtractor={(item) => String(item.id)}
          renderItem={({ item }) => (
            <TouchableOpacity style={styles.item} onPress={() => navigation.navigate('MusicaDetail', { musicaId: item.id })}>
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
  back: { color: '#2563eb', marginBottom: 16 },
  title: { fontSize: 20, fontWeight: '700', marginBottom: 16 },
  form: { marginBottom: 16 },
  row: { flexDirection: 'row', gap: 8, marginBottom: 8 },
  label: { marginBottom: 8, fontWeight: '600' },
  chipList: { marginBottom: 12, maxHeight: 44 },
  chip: { paddingHorizontal: 12, paddingVertical: 8, marginRight: 8, backgroundColor: '#e5e7eb', borderRadius: 20 },
  chipActive: { backgroundColor: '#2563eb' },
  chipText: { fontSize: 14 },
  chipTextActive: { color: '#fff' },
  input: { borderWidth: 1, borderColor: '#ccc', borderRadius: 8, padding: 12 },
  btn: { backgroundColor: '#2563eb', padding: 12, borderRadius: 8, alignSelf: 'flex-start' },
  btnText: { color: '#fff', fontWeight: '600' },
  loader: { marginTop: 24 },
  item: { padding: 16, borderBottomWidth: 1, borderBottomColor: '#eee' },
  itemTitle: { fontWeight: '600' },
  itemSub: { fontSize: 14, color: '#666', marginTop: 4 },
});

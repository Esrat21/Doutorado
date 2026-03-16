import React, { useEffect, useState } from 'react';
import { ActivityIndicator, FlatList, StyleSheet, Text, TextInput, TouchableOpacity, View } from 'react-native';
import { artistas as artistasApi } from '../api/client';

type ArtistaItem = { id: number; nome: string; slug: string };

import { useNavigation } from '@react-navigation/native';

export default function ArtistasScreen() {
  const navigation = useNavigation<any>();
  const onBack = () => navigation.goBack();
  const [list, setList] = useState<ArtistaItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [newNome, setNewNome] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const load = () => {
    artistasApi.list()
      .then((r) => setList(Array.isArray(r) ? r : (r?.data || [])))
      .catch(() => setList([]))
      .finally(() => setLoading(false));
  };

  useEffect(() => { load(); }, []);

  const add = async () => {
    if (!newNome.trim()) return;
    setSubmitting(true);
    try {
      const created = await artistasApi.create(newNome.trim());
      setList((prev) => [...prev, created]);
      setNewNome('');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <View style={styles.container}>
      <TouchableOpacity onPress={onBack}><Text style={styles.back}>← Voltar</Text></TouchableOpacity>
      <Text style={styles.title}>Artistas</Text>
      <View style={styles.row}>
        <TextInput style={styles.input} placeholder="Nome do artista" value={newNome} onChangeText={setNewNome} />
        <TouchableOpacity style={styles.btn} onPress={add} disabled={submitting}>
          <Text style={styles.btnText}>Adicionar</Text>
        </TouchableOpacity>
      </View>
      {loading ? <ActivityIndicator style={styles.loader} /> : (
        <FlatList
          data={list}
          keyExtractor={(item) => String(item.id)}
          renderItem={({ item }) => <View style={styles.item}><Text>{item.nome}</Text></View>}
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
  item: { padding: 16, borderBottomWidth: 1, borderBottomColor: '#eee' },
});

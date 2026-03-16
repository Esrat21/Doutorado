import React, { useEffect, useState } from 'react';
import { ActivityIndicator, ScrollView, StyleSheet, Text, View } from 'react-native';
import { musicas as musicasApi, versoes as versoesApi } from '../api/client';

type Versao = { id: number; numero_versao: number; titulo_versao?: string; conteudo: string };

import { useRoute, useNavigation } from '@react-navigation/native';

export default function MusicaDetailScreen() {
  const route = useRoute<any>();
  const navigation = useNavigation<any>();
  const musicaId = route.params?.musicaId ?? 0;
  const onBack = () => navigation.goBack();
  const [musica, setMusica] = useState<{ titulo: string; artista: { nome: string }; tom_original?: string } | null>(null);
  const [versoes, setVersoes] = useState<Versao[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    musicasApi.get(musicaId)
      .then((m) => {
        setMusica(m);
        return versoesApi.list(musicaId);
      })
      .then(setVersoes)
      .catch(() => setMusica(null))
      .finally(() => setLoading(false));
  }, [musicaId]);

  if (loading) return <ActivityIndicator style={styles.loader} />;
  if (!musica) return <Text style={styles.error}>Música não encontrada.</Text>;

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.back} onPress={onBack}>← Voltar</Text>
      <Text style={styles.title}>{musica.titulo}</Text>
      <Text style={styles.sub}>{musica.artista?.nome} {musica.tom_original ? `• ${musica.tom_original}` : ''}</Text>
      <Text style={styles.h2}>Versões (cifra)</Text>
      {versoes.length === 0 ? (
        <Text style={styles.empty}>Nenhuma versão.</Text>
      ) : (
        versoes.map((v) => (
          <View key={v.id} style={styles.versao}>
            <Text style={styles.versaoTitle}>Versão {v.numero_versao}{v.titulo_versao ? ` — ${v.titulo_versao}` : ''}</Text>
            <Text style={styles.conteudo}>{v.conteudo}</Text>
          </View>
        ))
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  loader: { flex: 1, marginTop: 24 },
  error: { padding: 24 },
  back: { color: '#2563eb', marginBottom: 16 },
  title: { fontSize: 20, fontWeight: '700' },
  sub: { color: '#666', marginTop: 4, marginBottom: 16 },
  h2: { fontSize: 18, fontWeight: '600', marginBottom: 12 },
  empty: { color: '#666' },
  versao: { marginBottom: 24, padding: 16, backgroundColor: '#f5f5f5', borderRadius: 8 },
  versaoTitle: { fontWeight: '600', marginBottom: 8 },
  conteudo: { fontFamily: 'monospace' },
});

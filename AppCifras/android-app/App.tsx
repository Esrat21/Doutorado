import { StatusBar } from 'expo-status-bar';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import { AuthProvider, useAuth } from './src/context/AuthContext';
import LoginScreen from './src/screens/LoginScreen';
import HomeScreen from './src/screens/HomeScreen';
import ArtistasScreen from './src/screens/ArtistasScreen';
import MusicasScreen from './src/screens/MusicasScreen';
import MusicaDetailScreen from './src/screens/MusicaDetailScreen';
import PlaylistsScreen from './src/screens/PlaylistsScreen';
import PlaylistDetailScreen from './src/screens/PlaylistDetailScreen';

const Stack = createNativeStackNavigator();

function MainStack() {
  return (
    <Stack.Navigator screenOptions={{ headerStyle: { backgroundColor: '#2563eb' }, headerTintColor: '#fff' }}>
      <Stack.Screen name="Home" component={HomeScreen} options={{ title: 'App Cifras' }} />
      <Stack.Screen name="Artistas" component={ArtistasScreen} />
      <Stack.Screen name="Musicas" component={MusicasScreen} />
      <Stack.Screen name="MusicaDetail" component={MusicaDetailScreen} options={{ title: 'Cifra' }} />
      <Stack.Screen name="Playlists" component={PlaylistsScreen} />
      <Stack.Screen name="PlaylistDetail" component={PlaylistDetailScreen} options={{ title: 'Playlist' }} />
    </Stack.Navigator>
  );
}

function Root() {
  const { token, loading } = useAuth();
  if (loading) return null;
  if (!token) return <LoginScreen onSuccess={() => {}} />;
  return <MainStack />;
}

export default function App() {
  return (
    <AuthProvider>
      <NavigationContainer>
        <Root />
      </NavigationContainer>
      <StatusBar style="auto" />
    </AuthProvider>
  );
}

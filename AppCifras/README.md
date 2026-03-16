# App Cifras

Backend central em **Laravel** (API) + **app web** (React) e **app Android** (Expo) consumindo a mesma API.

## Estrutura

| Pasta | Descrição |
|-------|-----------|
| **backend/** | API Laravel (PHP). Autenticação (Sanctum), usuários, artistas, músicas e versões de cifra. |
| **web/** | Aplicação web em React (Vite) que consome a API. |
| **android-app/** | App Android em React Native (Expo) que consome a mesma API. |

## Backend (Laravel)

### Requisitos
- PHP 8.2+
- Composer
- MySQL (ou SQLite para desenvolvimento)

### Configuração

```bash
cd backend
cp .env.example .env
php artisan key:generate
```

Edite `.env`: configure `DB_*` para MySQL (ou use SQLite com `DB_CONNECTION=sqlite` e remova `DB_DATABASE` ou use `database/database.sqlite`).

### Migrations e execução

```bash
cd backend
php artisan migrate
php artisan serve
```

API disponível em `http://localhost:8000`. Rotas em `/api/auth/*`, `/api/artistas`, `/api/musicas`, etc.

### CORS
Para o app web e o Android consumirem a API, configure as origens permitidas. Em desenvolvimento, o Laravel costuma liberar acesso. Se precisar, publique e edite `config/cors.php` ou use o pacote `fruitcake/laravel-cors`.

---

## App Web (React)

```bash
cd web
npm install
```

Crie `.env` com a URL da API (opcional):

```
VITE_API_URL=http://localhost:8000
```

```bash
npm run dev
```

Abre em `http://localhost:5173`. Login/cadastro e uso de artistas e músicas via API.

---

## App Android (Expo)

```bash
cd android-app
npm install
```

Para o dispositivo/emulador acessar a API, use o IP da sua máquina (não `localhost`). Crie `.env` ou defina:

```
EXPO_PUBLIC_API_URL=http://192.168.1.X:8000
```

(Substitua pelo IP real da máquina onde o Laravel está rodando.)

```bash
npm run android
```

Ou `npm start` e escaneie o QR code com o Expo Go.

---

## Endpoints da API (resumo)

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | /api/auth/register | Cadastro (name, email, password) |
| POST | /api/auth/login | Login (email, password) → retorna token |
| POST | /api/auth/logout | Logout (Bearer token) |
| GET | /api/auth/me | Usuário atual (Bearer token) |
| GET/POST | /api/artistas | Listar / criar artista |
| GET | /api/artistas/{id} | Detalhe do artista |
| GET/POST | /api/musicas | Listar / criar música (Bearer) |
| GET/PUT/DELETE | /api/musicas/{id} | Detalhe / atualizar / excluir música |
| GET/POST | /api/musicas/{id}/versoes | Listar / criar versão de cifra |
| GET/POST | /api/playlists | Listar / criar playlist (Bearer) |
| GET/PUT/DELETE | /api/playlists/{id} | Detalhe / atualizar / excluir playlist |
| POST | /api/playlists/{id}/musicas | Adicionar música (body: musica_id) |
| DELETE | /api/playlists/{id}/musicas/{musica_id} | Remover música da playlist |

Todas as rotas protegidas usam o header: `Authorization: Bearer {token}`.

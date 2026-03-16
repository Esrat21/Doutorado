<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Importa cifra a partir de uma URL do Cifra Club.
 * Usa a página "imprimir" para obter o conteúdo em texto.
 */
class CifraClubImporter
{
    /**
     * Extrai artista, título, tom e texto da cifra da URL do Cifra Club.
     *
     * @return array{artista: string, titulo: string, tom: string|null, conteudo: string}
     */
    public static function fetchFromUrl(string $url): array
    {
        $imprimirUrl = self::toImprimirUrl($url);
        $http = Http::withHeaders(['User-Agent' => 'AppCifras/1.0']);
        // Em ambiente local, desabilita verificação de SSL para evitar erro de certificado
        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }
        $response = $http->timeout(15)->get($imprimirUrl);

        if (! $response->successful()) {
            throw new \RuntimeException('Não foi possível acessar a página do Cifra Club. Verifique a URL.');
        }

        $html = $response->body();
        $artista = self::extractArtist($html);
        $titulo = self::extractTitle($html);
        $tom = self::extractTom($html);
        $conteudo = self::extractCifraContent($html);

        if ($conteudo === '') {
            throw new \RuntimeException('Cifra não encontrada nesta página.');
        }

        return [
            'artista' => $artista,
            'titulo' => $titulo,
            'tom' => $tom,
            'conteudo' => trim($conteudo),
        ];
    }

    /**
     * Extrai da página do artista todas as URLs de músicas (cifras).
     *
     * @return array{artista: string, urls: string[]}
     */
    public static function fetchArtistSongUrls(string $artistUrl): array
    {
        $url = trim($artistUrl);
        if ($url === '' || ! Str::contains($url, 'cifraclub.com.br')) {
            throw new \InvalidArgumentException('URL deve ser do Cifra Club (página do artista).');
        }
        // remove barra final sem usar regex para evitar problemas com modificadores
        $url = rtrim($url, '/');
        $url = preg_replace('#/(musicas|discografia|videoaulas)(\.html)?.*$#i', '', $url);

        $http = Http::withHeaders(['User-Agent' => 'AppCifras/1.0']);
        // Em ambiente local, desabilita verificação de SSL para evitar erro de certificado
        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }
        $response = $http->timeout(20)->get($url);

        if (! $response->successful()) {
            throw new \RuntimeException('Não foi possível acessar a página do artista.');
        }

        $html = $response->body();
        $artista = self::extractArtistFromArtistPage($html, $url);
        $urls = self::extractSongUrlsFromArtistPage($html, $url);

        // A página principal mostra só "músicas populares" (~15). A lista completa está em /artista/musicas.html
        $baseUrl = (parse_url($url, PHP_URL_SCHEME) ?: 'https') . '://'
            . (parse_url($url, PHP_URL_HOST) ?: 'www.cifraclub.com.br')
            . (parse_url($url, PHP_URL_PATH) ?? '');
        $baseUrl = rtrim($baseUrl, '/');
        $musicasPageUrl = $baseUrl . '/musicas.html';
        if ($musicasPageUrl !== $url) {
            $responseMusicas = $http->timeout(25)->get($musicasPageUrl);
            if ($responseMusicas->successful()) {
                $htmlMusicas = $responseMusicas->body();
                $urlsMusicas = self::extractSongUrlsFromArtistPage($htmlMusicas, $url);
                $urls = array_values(array_unique(array_merge($urls, $urlsMusicas)));
            }
        }

        return [
            'artista' => $artista,
            'urls' => $urls,
        ];
    }

    /**
     * Para uma URL de música no Cifra Club, retorna todas as URLs de versões (principal, simplificada, etc.).
     *
     * @return string[]
     */
    public static function getVersionUrlsForSong(string $songUrl): array
    {
        $songUrl = trim($songUrl);
        if ($songUrl === '' || ! Str::contains($songUrl, 'cifraclub.com.br')) {
            return [];
        }
        $parsed = parse_url($songUrl);
        $host = $parsed['host'] ?? '';
        if ($host !== 'www.cifraclub.com.br' && $host !== 'cifraclub.com.br') {
            return [];
        }
        $path = $parsed['path'] ?? '';
        $path = trim($path, '/');
        $segments = $path !== '' ? explode('/', $path) : [];
        if (count($segments) < 2) {
            return [$songUrl];
        }
        $songBasePath = '/' . implode('/', array_slice($segments, 0, 2));
        $baseUrl = (parse_url($songUrl, PHP_URL_SCHEME) ?: 'https') . '://' . $host;

        $http = Http::withHeaders(['User-Agent' => 'AppCifras/1.0']);
        if (app()->environment('local')) {
            $http = $http->withOptions(['verify' => false]);
        }
        $response = $http->timeout(15)->get(rtrim($songUrl, '/'));
        if (! $response->successful()) {
            return [rtrim($songUrl, '/')];
        }
        $html = $response->body();
        $versionUrls = [rtrim($songUrl, '/') => true];
        if (preg_match_all('~href="(https?://(?:www\.)?cifraclub\.com\.br(/[^"]*))"~iu', $html, $m)) {
            foreach ($m[2] as $pathAttr) {
                $pathAttr = trim($pathAttr, '/');
                if ($pathAttr === '') {
                    continue;
                }
                $pathNorm = '/' . $pathAttr;
                if ($pathNorm !== $songBasePath && ! str_starts_with($pathNorm, $songBasePath . '/')) {
                    continue;
                }
                if (str_contains($pathAttr, '/discografia/') || str_contains($pathAttr, 'videoaulas') || str_contains($pathAttr, '/blog/')) {
                    continue;
                }
                $full = $baseUrl . '/' . $pathAttr;
                $full = explode('#', $full)[0];
                $full = explode('?', $full)[0];
                $full = rtrim($full, '/');
                if (strlen($full) > 30) {
                    $versionUrls[$full] = true;
                }
            }
        }

        return array_keys($versionUrls);
    }

    private static function stripCifraClubFromName(string $name): string
    {
        $name = trim($name);
        $name = preg_replace('/\s*[-|–]\s*Cifra Club.*$/iu', '', $name);
        $name = preg_replace('/^Cifra Club\s*[-|–]\s*/iu', '', $name);
        $name = preg_replace('/\s*[-|–]\s*Cifra Club\s*$/iu', '', $name);
        $name = preg_replace('/^Cifra Club\s*$/iu', '', $name);

        return trim($name);
    }

    private static function extractArtistFromArtistPage(string $html, string $currentUrl): string
    {
        if (preg_match('/<h1[^>]*>([^<]+)<\/h1>/iu', $html, $m)) {
            $name = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            return self::stripCifraClubFromName($name) ?: 'Artista';
        }
        if (preg_match('/<title[^>]*>([^<]+)/iu', $html, $m)) {
            $t = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $t = self::stripCifraClubFromName($t);

            return $t ?: 'Artista';
        }
        if (preg_match('#cifraclub\.com\.br/([^/]+)/#', $currentUrl, $m)) {
            return str_replace(['-', '_'], ' ', ucwords($m[1], '-'));
        }

        return 'Artista';
    }

    private static function extractSongUrlsFromArtistPage(string $html, string $artistPageUrl): array
    {
        $urls = [];

        // identifica o slug do artista a partir da URL da página (ex: /legiao-urbana/)
        $parsedArtist = parse_url($artistPageUrl);
        $artistPath = $parsedArtist['path'] ?? '/';
        $artistPath = trim($artistPath, '/');
        $artistSegments = $artistPath !== '' ? explode('/', $artistPath) : [];
        $artistSlug = $artistSegments[0] ?? '';

        // primeira tentativa: links absolutos já apontando para o cifraclub,
        // filtrando pelo mesmo artista via slug na própria URL
        if (preg_match_all('~href="(https?://[^"]*cifraclub\.com\.br/[^"/?#]+(?:/|\.html)?[^"]*)"~iu', $html, $m)) {
            foreach ($m[1] as $href) {
                // remove fragmento (#...) e query (?...) sem regex para evitar problemas de modificador
                $clean = strtok($href, '#');
                $clean = strtok($clean, '?');
                $clean = preg_replace('~/(simplificada|imprimir|tabs-baixo|aula-completa|videoaulas|discografia)[^/]*\.?html?~iu', '', $clean);
                $clean = rtrim($clean, '/');

                $parsed = parse_url($clean);
                $host = $parsed['host'] ?? '';
                $path = $parsed['path'] ?? '';

                // mantém apenas domínio principal do Cifra Club (sem subdomínios como id., suporte, etc.)
                if ($host !== 'www.cifraclub.com.br' && $host !== 'cifraclub.com.br') {
                    continue;
                }

                // garante que a URL é para o mesmo artista: /{slug}/alguma-coisa
                if ($artistSlug !== '') {
                    if (! str_starts_with($path, '/' . $artistSlug . '/')) {
                        continue;
                    }
                }

                if (str_contains($clean, '/discografia/') || str_contains($clean, '/blog/') || str_contains($clean, '/estilos/')) {
                    continue;
                }
                if (strlen($clean) > 20 && ! in_array($clean, $urls, true)) {
                    $urls[] = $clean;
                }
            }
        }

        // fallback: links relativos /artista/musica/
        if (empty($urls) && preg_match_all('~href="(/[^"]+)"~u', $html, $m)) {
            $baseUrl = 'https://www.cifraclub.com.br';
            $seen = [];
            foreach ($m[1] as $path) {
                // mantém apenas caminhos relativos do mesmo artista, ex: /legiao-urbana/tempo-perdido/
                $cleanPath = strtok($path, '?');
                $cleanPath = rtrim($cleanPath, '/');

                if ($artistSlug !== '') {
                    if (! str_starts_with($cleanPath, '/' . $artistSlug . '/')) {
                        continue;
                    }
                }

                if (str_contains($cleanPath, 'discografia') || str_contains($cleanPath, 'musicas.html') || str_contains($cleanPath, 'videoaulas') || str_contains($cleanPath, 'blog')) {
                    continue;
                }
                $full = $baseUrl . $cleanPath;
                if (! in_array($full, $seen, true) && substr_count($cleanPath, '/') >= 2) {
                    $seen[] = $full;
                    $urls[] = $full;
                }
            }
        }

        return array_values(array_unique($urls));
    }

    private static function toImprimirUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            throw new \InvalidArgumentException('URL é obrigatória.');
        }
        if (! Str::contains($url, 'cifraclub.com.br')) {
            throw new \InvalidArgumentException('URL deve ser do Cifra Club (cifraclub.com.br).');
        }
        // remove barra final sem usar regex para evitar problemas com modificadores
        $url = rtrim($url, '/');
        if (Str::contains($url, '/imprimir')) {
            return $url;
        }
        if (Str::endsWith($url, '.html')) {
            $url = preg_replace('#\.html$#', '', $url);
        }

        return rtrim($url, '/') . '/imprimir.html';
    }

    private static function extractTitle(string $html): string
    {
        if (preg_match('/<h1[^>]*>([^<]+)<\/h1>/iu', $html, $m)) {
            $t = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            return self::stripCifraClubFromName($t) ?: 'Cifra importada';
        }
        if (preg_match('/<title[^>]*>([^<]+)/iu', $html, $m)) {
            $t = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $t = self::stripCifraClubFromName($t);

            return $t ?: 'Cifra importada';
        }

        return 'Cifra importada';
    }

    private static function extractArtist(string $html): string
    {
        if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/iu', $html, $m)) {
            $name = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

            return self::stripCifraClubFromName($name) ?: 'Artista';
        }
        if (preg_match('/Cifra Club\s*[-|–]\s*([^|-]+)/iu', $html, $m)) {
            return self::stripCifraClubFromName(trim($m[1]));
        }

        return 'Artista';
    }

    private static function extractTom(string $html): ?string
    {
        if (preg_match('/tom:\s*([A-G][#b]?m?)/iu', $html, $m)) {
            return trim($m[1]);
        }

        return null;
    }

    private static function extractCifraContent(string $html): string
    {
        $html = preg_replace('/<script[^>]*>[\s\S]*?<\/script>/iu', '', $html);
        $html = preg_replace('/<style[^>]*>[\s\S]*?<\/style>/iu', '', $html);

        if (preg_match('/<pre[^>]*>([\s\S]*?)<\/pre>/iu', $html, $m)) {
            $text = $m[1];
            $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return trim(self::normalizeLineBreaks($text));
        }
        if (preg_match('/<code[^>]*>([\s\S]*?)<\/code>/iu', $html, $m)) {
            $text = $m[1];
            $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return trim(self::normalizeLineBreaks($text));
        }
        if (preg_match('/\[Intro\]/u', $html, $m, PREG_OFFSET_CAPTURE)) {
            $start = $m[0][1];
            $chunk = substr($html, $start, 80000);
            $chunk = strip_tags($chunk);
            $chunk = html_entity_decode($chunk, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $chunk = self::normalizeLineBreaks($chunk);
            $chunk = trim($chunk);
            if (preg_match('/^.+/um', $chunk)) {
                return $chunk;
            }
        }
        if (preg_match('/\[Verso\]|\[Refrão\]|\[Tab\s/u', $html, $m, PREG_OFFSET_CAPTURE)) {
            $start = max(0, $m[0][1] - 200);
            $chunk = substr($html, $start, 80000);
            $chunk = strip_tags($chunk);
            $chunk = html_entity_decode($chunk, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $chunk = self::normalizeLineBreaks($chunk);
            $chunk = trim(preg_replace('/^[\s\S]*?\[/u', '[', $chunk));

            return trim($chunk);
        }

        return '';
    }

    private static function normalizeLineBreaks(string $s): string
    {
        $s = str_replace(["\r\n", "\r"], "\n", $s);

        return preg_replace("/\n{3,}/u", "\n\n", $s);
    }
}

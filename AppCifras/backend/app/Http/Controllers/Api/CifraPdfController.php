<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Musica;
use App\Models\MusicaVersao;
use App\Services\ChordTransposer;
use App\Services\CifraEstruturaHelper;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;

class CifraPdfController extends Controller
{
    public function __invoke(Request $request, Musica $musica, MusicaVersao $versao): Response
    {
        $user = $request->user();
        if (! $user) {
            abort(401, 'É preciso estar autenticado para exportar PDF.');
        }
        if ($versao->musica_id !== $musica->id || $musica->usuario_criador_id !== $user->id) {
            abort(403, 'Não autorizado.');
        }

        $semitones = (int) $request->get('semitones', 0);
        $capo = (int) $request->get('capo', 0);
        $capo = max(0, min(12, $capo));

        $estrutura = $versao->estrutura_json;
        if (is_array($estrutura) && ! empty($estrutura['secoes'] ?? [])) {
            $estruturaExibir = ChordTransposer::transposeEstrutura($estrutura, $semitones);
            $conteudoExibir = CifraEstruturaHelper::estruturaToText($estruturaExibir);
            $estruturaTomReal = $capo > 0 ? ChordTransposer::transposeEstrutura($estrutura, $semitones + $capo) : null;
            $conteudoTomReal = $estruturaTomReal !== null ? CifraEstruturaHelper::estruturaToText($estruturaTomReal) : null;
        } else {
            $conteudoOriginal = $versao->conteudo ?? '';
            $conteudoExibir = ChordTransposer::transpose($conteudoOriginal, $semitones);
            $conteudoTomReal = $capo > 0 ? ChordTransposer::transpose($conteudoOriginal, $semitones + $capo) : null;
        }

        $titulo = e($musica->titulo);
        $artista = e($musica->artista->nome ?? '');
        $versaoLabel = $versao->titulo_versao ? e($versao->titulo_versao) : "Versão {$versao->numero_versao}";
        $conteudoHtml = nl2br(e($conteudoExibir));
        $conteudoTomRealHtml = $conteudoTomReal !== null && $conteudoTomReal !== '' ? nl2br(e($conteudoTomReal)) : '';

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 11pt; line-height: 1.5; color: #333; }
h1 { font-size: 18pt; margin-bottom: 4px; }
.sub { font-size: 12pt; color: #666; margin-bottom: 16px; }
.versao { font-size: 10pt; color: #888; margin-bottom: 20px; }
.block { margin-bottom: 24px; }
.block h2 { font-size: 12pt; margin-bottom: 8px; color: #555; }
.pre { white-space: pre-wrap; font-family: monospace; font-size: 10pt; }
.two-cols { display: table; width: 100%; }
.col { display: table-cell; width: 50%; vertical-align: top; padding-right: 16px; }
</style>
</head>
<body>
<h1>{$titulo}</h1>
<p class="sub">{$artista}</p>
<p class="versao">{$versaoLabel}</p>
HTML;

        if ($capo > 0 && $conteudoTomRealHtml !== '') {
            $html .= '<div class="two-cols">';
            $html .= '<div class="col"><div class="block"><h2>Com capotraste (casa ' . $capo . ')</h2><div class="pre">' . $conteudoHtml . '</div></div></div>';
            $html .= '<div class="col"><div class="block"><h2>Tom real</h2><div class="pre">' . $conteudoTomRealHtml . '</div></div></div>';
            $html .= '</div>';
        } else {
            $html .= '<div class="block"><div class="pre">' . $conteudoHtml . '</div></div>';
        }

        $html .= '</body></html>';

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
        $mpdf->WriteHTML($html);
        $pdf = $mpdf->Output('', 'S');

        $filename = \Illuminate\Support\Str::slug($musica->titulo . '-' . ($musica->artista->nome ?? '')) . '.pdf';

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($pdf),
        ]);
    }
}

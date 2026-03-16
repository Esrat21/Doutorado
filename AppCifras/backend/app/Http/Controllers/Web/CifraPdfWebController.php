<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Musica;
use App\Models\MusicaVersao;
use App\Services\ChordTransposer;
use App\Services\CifraEstruturaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;
use Symfony\Component\HttpFoundation\Response;

class CifraPdfWebController extends Controller
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
        $fontSize = (int) $request->get('fontSize', 9);
        $fontSize = max(9, min(14, $fontSize));
        $columns = (int) $request->get('columns', 2);
        $columns = $columns === 2 ? 2 : 1;

        $estrutura = $versao->estrutura_json;
        $estruturaExibir = null;
        $conteudoExibir = '';
        if (is_array($estrutura) && ! empty($estrutura['secoes'] ?? [])) {
            $estruturaExibir = ChordTransposer::transposeEstrutura($estrutura, $semitones);
            $conteudoExibir = CifraEstruturaHelper::estruturaToText($estruturaExibir);
        } else {
            $conteudoOriginal = $versao->conteudo ?? '';
            $conteudoExibir = ChordTransposer::transpose($conteudoOriginal, $semitones);
        }

        $titulo = $musica->titulo;
        $artista = $musica->artista->nome ?? '';
        $versaoLabel = $versao->titulo_versao ?: "Versão {$versao->numero_versao}";
        $capoLabel = $capo > 0 ? "Capotraste: {$capo}ª casa" : 'Sem capotraste';
        $conteudoHtml = nl2br(CifraEstruturaHelper::highlightChordsInHtml($conteudoExibir));

        $preFontSize = max(9, $fontSize - 1);
        $colCss = $columns === 2 ? 'column-count: 2; column-gap: 20px;' : '';

        $html = View::make('web.musica-pdf', [
            'titulo' => $titulo,
            'artista' => $artista,
            'versaoLabel' => $versaoLabel,
            'capoLabel' => $capoLabel,
            'estrutura' => $estruturaExibir,
            'conteudoHtml' => $conteudoHtml,
            'fontSize' => $fontSize,
            'preFontSize' => $preFontSize,
            'colCss' => $colCss,
        ])->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
        // CSS em modo raw (HEADER_CSS) e depois corpo HTML para mPDF aplicar estilos corretamente
        $css = $this->extractCssForMpdf($html);
        if ($css !== '') {
            $mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
        }
        $body = $this->extractBodyForMpdf($html);
        $mpdf->WriteHTML($body, HTMLParserMode::HTML_BODY);
        $pdf = $mpdf->Output('', 'S');

        $filename = \Illuminate\Support\Str::slug($musica->titulo . '-' . ($musica->artista->nome ?? '')) . '.pdf';

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($pdf),
        ]);
    }

    /** Extrai o conteúdo do primeiro <style> do HTML para passar ao mPDF em modo HEADER_CSS. */
    private function extractCssForMpdf(string $html): string
    {
        if (preg_match('/<style[^>]*>(.*?)<\/style>/s', $html, $m)) {
            return trim($m[1]);
        }

        return '';
    }

    /** Extrai o conteúdo do <body> para passar ao mPDF em modo HTML_BODY. */
    private function extractBodyForMpdf(string $html): string
    {
        if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $html, $m)) {
            return $m[1];
        }

        return $html;
    }
}

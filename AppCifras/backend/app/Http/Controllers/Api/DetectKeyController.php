<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ChordKeyDetector;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DetectKeyController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['conteudo' => 'required|string']);
        $tom = ChordKeyDetector::detectFromContent($request->conteudo);
        return response()->json(['tom' => $tom]);
    }
}

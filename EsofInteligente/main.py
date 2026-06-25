import os
import json
import csv
import shutil
import subprocess
import requests
from pathlib import Path
from prompts import montar_prompt

BASE_DIR = Path(__file__).parent
GENERATED_DIR = BASE_DIR / "generated"
TESTS_DIR = BASE_DIR / "tests"
RESULTS_FILE = BASE_DIR / "results.csv"

OLLAMA_URL = "http://localhost:11434/api/chat"

MODELOS = [
    "qwen3:8b",
    "gemma3:4b",
]


def carregar_problemas():
    with open(BASE_DIR / "problemas.json", "r", encoding="utf-8") as arquivo:
        return json.load(arquivo)


def limpar_codigo(codigo: str) -> str:
    codigo = codigo.strip()

    if codigo.startswith("```python"):
        codigo = codigo.replace("```python", "", 1)
        codigo = codigo.rsplit("```", 1)[0]
    elif codigo.startswith("```"):
        codigo = codigo.replace("```", "", 1)
        codigo = codigo.rsplit("```", 1)[0]

    return codigo.strip()


def gerar_codigo(modelo: str, prompt: str) -> str:
    payload = {
        "model": modelo,
        "messages": [
            {"role": "user", "content": prompt}
        ],
        "stream": False,
        "options": {
            "temperature": 0.2,
            "num_predict": 1000
        }
    }

    resposta = requests.post(OLLAMA_URL, json=payload, timeout=120)
    resposta.raise_for_status()

    dados = resposta.json()
    codigo = dados["message"]["content"]
    return limpar_codigo(codigo)


def executar_teste(caminho_codigo: Path, arquivo_teste: str):
    temp_dir = BASE_DIR / "temp_exec"

    if temp_dir.exists():
        shutil.rmtree(temp_dir)

    temp_dir.mkdir()

    shutil.copy(caminho_codigo, temp_dir / "solution.py")
    shutil.copy(TESTS_DIR / arquivo_teste, temp_dir / arquivo_teste)

    comando = ["pytest", arquivo_teste, "-q"]

    try:
        resultado = subprocess.run(
            comando,
            cwd=temp_dir,
            capture_output=True,
            text=True,
            timeout=30
        )

        saida = resultado.stdout + resultado.stderr
        return resultado.returncode, saida

    except subprocess.TimeoutExpired:
        return 1, "TIMEOUT: execução excedeu 30 segundos"

    finally:
        if temp_dir.exists():
            shutil.rmtree(temp_dir)


def extrair_resultado(saida: str):
    palavras = saida.replace(",", "").split()

    aprovados = 0
    falhas = 0

    for i, palavra in enumerate(palavras):
        if palavra == "passed" and i > 0 and palavras[i - 1].isdigit():
            aprovados = int(palavras[i - 1])

        if palavra == "failed" and i > 0 and palavras[i - 1].isdigit():
            falhas = int(palavras[i - 1])

    total = aprovados + falhas
    return aprovados, total


def salvar_resultado(linha: dict):
    arquivo_existe = RESULTS_FILE.exists()

    with open(RESULTS_FILE, "a", encoding="utf-8", newline="") as arquivo:
        campos = [
            "problema_id",
            "titulo",
            "nivel",
            "modelo",
            "estrategia",
            "testes_aprovados",
            "total_testes",
            "correcao_funcional",
            "erro"
        ]

        writer = csv.DictWriter(arquivo, fieldnames=campos)

        if not arquivo_existe:
            writer.writeheader()

        writer.writerow(linha)


def nome_arquivo_seguro(modelo: str) -> str:
    # "qwen3:8b" -> "qwen3-8b" (':' não é seguro em nomes de arquivo)
    return modelo.replace(":", "-").replace("/", "-")


def main():
    GENERATED_DIR.mkdir(exist_ok=True)

    problemas = carregar_problemas()

    for modelo in MODELOS:
        print(f"\n=== Rodando modelo: {modelo} ===")

        for problema in problemas:
            print(f"Executando {problema['id']} - {problema['titulo']} ({modelo})")

            prompt = montar_prompt(problema)

            try:
                codigo = gerar_codigo(modelo, prompt)
                falhou_geracao = False
            except (requests.RequestException, KeyError) as exc:
                print(f"  Erro ao gerar código com {modelo}: {exc}")
                codigo = ""
                falhou_geracao = True

            nome_arquivo = f"{problema['id']}_{nome_arquivo_seguro(modelo)}_zero_shot.py"
            caminho_codigo = GENERATED_DIR / nome_arquivo

            with open(caminho_codigo, "w", encoding="utf-8") as arquivo:
                arquivo.write(codigo)

            if falhou_geracao:
                aprovados, total, correcao = 0, 0, 0
                returncode = 1
            else:
                returncode, saida = executar_teste(
                    caminho_codigo,
                    problema["arquivo_teste"]
                )
                aprovados, total = extrair_resultado(saida)
                correcao = round((aprovados / total) * 100, 2) if total > 0 else 0

            salvar_resultado({
                "problema_id": problema["id"],
                "titulo": problema["titulo"],
                "nivel": problema["nivel"],
                "modelo": modelo,
                "estrategia": "zero-shot",
                "testes_aprovados": aprovados,
                "total_testes": total,
                "correcao_funcional": correcao,
                "erro": returncode != 0
            })

            print(f"  Resultado: {aprovados}/{total} testes aprovados - {correcao}%")


if __name__ == "__main__":
    main()
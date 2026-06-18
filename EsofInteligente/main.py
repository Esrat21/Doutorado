import os
import json
import csv
import shutil
import subprocess
from pathlib import Path
from dotenv import load_dotenv
from openai import OpenAI
from prompts import montar_prompt

load_dotenv()

client = OpenAI(api_key=os.getenv("OPENAI_API_KEY"))

BASE_DIR = Path(__file__).parent
GENERATED_DIR = BASE_DIR / "generated"
TESTS_DIR = BASE_DIR / "tests"
RESULTS_FILE = BASE_DIR / "results.csv"


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


def gerar_codigo(prompt: str) -> str:
    resposta = client.chat.completions.create(
        model="gpt-4-turbo",
        messages=[
            {"role": "user", "content": prompt}
        ],
        temperature=0.2,
        max_tokens=1000
    )

    codigo = resposta.choices[0].message.content
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


def main():
    GENERATED_DIR.mkdir(exist_ok=True)

    problemas = carregar_problemas()

    for problema in problemas:
        print(f"Executando {problema['id']} - {problema['titulo']}")

        prompt = montar_prompt(problema)
        codigo = gerar_codigo(prompt)

        nome_arquivo = f"{problema['id']}_gpt4_zero_shot.py"
        caminho_codigo = GENERATED_DIR / nome_arquivo

        with open(caminho_codigo, "w", encoding="utf-8") as arquivo:
            arquivo.write(codigo)

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
            "modelo": "qwen-3-6",
            "estrategia": "zero-shot",
            "testes_aprovados": aprovados,
            "total_testes": total,
            "correcao_funcional": correcao,
            "erro": returncode != 0
        })

        print(f"Resultado: {aprovados}/{total} testes aprovados - {correcao}%")


if __name__ == "__main__":
    main()

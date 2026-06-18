def montar_prompt(problema: dict) -> str:
    return f"""
Você é um programador Python especializado em teoria musical.

Resolva o problema abaixo usando Python.

Título:
{problema["titulo"]}

Descrição:
{problema["descricao"]}

Assinatura obrigatória da função:
{problema["assinatura"]}

Regras:
- Responda apenas com código Python.
- Não escreva explicações.
- Não use bibliotecas externas.
- O código precisa funcionar com pytest.
""".strip()

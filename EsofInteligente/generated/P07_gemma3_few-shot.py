def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # Regra 1: Deve terminar em I
    if graus[-1] != "I":
        return False

    # Regra 2: Deve ter pelo menos um IV ou V antes da resolução final (graus[:-1])
    progressao_anterior = graus[:-1]
    encontrou_iv_ou_v = any(g in ["IV", "V"] for g in progressao_anterior)

    return encontrou_iv_ou_v
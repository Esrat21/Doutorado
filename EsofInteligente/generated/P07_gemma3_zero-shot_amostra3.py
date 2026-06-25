def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # 1. Deve terminar em I
    if graus[-1] != "I":
        return False

    # 2. Deve conter IV ou V antes da resolução final (todos os elementos exceto o último)
    pre_resolution = graus[:-1]

    has_iv_or_v = any(grau in ["IV", "V"] for grau in pre_resolution)

    return has_iv_or_v
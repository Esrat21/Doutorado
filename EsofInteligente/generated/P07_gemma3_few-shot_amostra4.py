def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # Regra 1: Deve terminar em I
    if graus[-1] != "I":
        return False

    # Progressão antes da resolução (todos exceto o último elemento)
    progressao_anterior = graus[:-1]

    # Se a progressão anterior estiver vazia, não há como cumprir a regra 2.
    if not progressao_anterior:
        return False

    # Regra 2: Deve conter pelo menos um IV ou V antes da resolução final
    for grau in progressao_anterior:
        if grau == "IV" or grau == "V":
            return True

    return False
def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # 1. Deve terminar em I
    if graus[-1] != "I":
        return False

    # 2. Deve conter IV ou V antes da resolução final (todos os elementos exceto o último)
    progressao_antes = graus[:-1]
    
    for grau in progressao_antes:
        if grau == "IV" or grau == "V":
            return True

    return False
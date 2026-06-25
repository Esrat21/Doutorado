def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # Rule 1: Must end in I
    if graus[-1] != "I":
        return False

    # Check if IV or V exists before the last element (the resolution)
    graus_antes_resolucao = graus[:-1]
    
    has_iv_or_v = any(grau in ["IV", "V"] for grau in graus_antes_resolucao)

    return has_iv_or_v
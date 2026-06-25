def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # Rule 1: Must end in I
    if graus[-1] != "I":
        return False

    # Rule 2: Must contain at least one IV or V before the final resolution (excluding the last element)
    graus_anteriores = graus[:-1]
    
    has_iv_or_v = any(g in ["IV", "V"] for g in graus_anteriores)

    return has_iv_or_v
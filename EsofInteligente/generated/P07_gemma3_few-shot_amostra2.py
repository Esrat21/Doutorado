def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    if graus[-1] != "I":
        return False

    pre_degrees = graus[:-1]
    has_iv_or_v = any(g in ["IV", "V"] for g in pre_degrees)

    return has_iv_or_v
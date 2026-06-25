def validar_progressao(graus: list[str]) -> bool:
    if not graus or graus[-1] != "I":
        return False

    graus_anteriores = graus[:-1]

    if not graus_anteriores:
        return False

    has_iv = "IV" in graus_anteriores
    has_v = "V" in graus_anteriores

    return has_iv or has_v
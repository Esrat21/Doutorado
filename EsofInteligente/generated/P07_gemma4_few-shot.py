def validar_progressao(graus: list[str]) -> bool:
    if not graus or graus[-1] != "I":
        return False

    has_iv = "IV" in graus
    has_v = "V" in graus

    return has_iv or has_v
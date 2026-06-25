def validar_progressao(graus: list[str]) -> bool:
    if not graus or graus[-1] != "I":
        return False
    return "IV" in graus[:-1] or "V" in graus[:-1]
def validar_progressao(graus: list[str]) -> bool:
    if not graus or graus[-1] != "I":
        return False
    for grau in graus[:-1]:
        if grau in ("IV", "V"):
            return True
    return False
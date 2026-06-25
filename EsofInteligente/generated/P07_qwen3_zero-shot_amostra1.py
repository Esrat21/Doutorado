def validar_progressao(graus: list[str]) -> bool:
    if not graus or graus[-1] != "I":
        return False
    return any(g in ("IV", "V") for g in graus[:-1])
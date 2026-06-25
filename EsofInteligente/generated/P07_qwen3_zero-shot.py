def validar_progressao(graus: list[str]) -> bool:
    if not graus or graus[-1] != "I":
        return False
    return any(d in ("IV", "V") for d in graus[:-1])
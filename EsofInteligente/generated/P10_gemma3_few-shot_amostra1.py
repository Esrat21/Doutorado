def detectar_modulacao(tonalidades: list[str]) -> bool:
    if not tonalidades:
        return False
    unique_tonalities = set(tonalidades)
    return len(unique_tonalities) > 1
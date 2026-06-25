def detectar_modulacao(tonalidades: list[str]) -> bool:
    if len(tonalidades) < 2:
        return False
    return any(a != b for a, b in zip(tonalidades[:-1], tonalidades[1:]))
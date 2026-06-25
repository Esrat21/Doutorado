def detectar_modulacao(tonalidades: list[str]) -> bool:
    if len(tonalidades) < 2:
        return False

    for i in range(1, len(tonalidades)):
        if tonalidades[i] != tonalidades[i-1]:
            return True
    return False
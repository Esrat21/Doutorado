def detectar_modulacao(tonalidades: list[str]) -> bool:
    for i in range(len(tonalidades) - 1):
        if tonalidades[i] != tonalidades[i + 1]:
            return True
    return False
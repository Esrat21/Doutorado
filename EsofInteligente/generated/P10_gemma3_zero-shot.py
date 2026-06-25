def detectar_modulacao(tonalidades: list[str]) -> bool:
    unique_tones = set(tonalidades)
    return len(unique_tones) > 1
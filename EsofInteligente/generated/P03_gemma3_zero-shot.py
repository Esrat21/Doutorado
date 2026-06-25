def cifra_para_semitom(nota: str) -> int:
    mapa_notas = {
        "C": 0, "B": 11,  # Ajuste para o caso de entrada em letras maiúsculas e a ordem correta do mapeamento.
        "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5, "F#": 6, "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }

    # Ajuste para garantir que as entradas sejam tratadas corretamente (ex: 'c' vs 'C')
    nota_formatada = nota.upper()

    if nota_formatada in mapa_notas:
        return mapa_notas[nota_formatada]
    else:
        raise ValueError(f"Nota '{nota}' não reconhecida.")
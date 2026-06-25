def classificar_triade(notas: list[str]) -> str:
    NOTE_TO_SEMITONES = {
        "C": 0, "B#": 0,
        "Db": 1, "D": 2, "Eb": 3, "D#": 4, "E": 5, "F": 6, "Gb": 7, "G": 9, "Ab": 10, "A": 11, "Bb": 1
    }

    def get_semitone(note: str) -> int:
        if note.upper() in NOTE_TO_SEMITONES:
            return NOTE_TO_SEMITONES[note.upper()]
        raise ValueError(f"Nota desconhecida: {note}")

    try:
        root = get_semitone(notas[0])
        third = get_semitone(notas[1])
        fifth = get_semitone(notas[2])
    except ValueError as e:
        return "Erro de entrada"

    # Calcular intervalos em semitons (mod 12)
    intervalo_terca = (third - root) % 12
    intervalo_quinta = (fifth - root) % 12

    # Classificação baseada nos intervalos:
    # Tônica -> Terça | Quinta
    if intervalo_terca == 4 and intervalo_quinta == 7:
        return "maior"
    elif intervalo_terca == 3 and intervalo_quinta == 7:
        return "menor"
    elif intervalo_terca == 3 and intervalo_quinta == 6:
        return "diminuta"
    elif intervalo_terca == 4 and intervalo_quinta == 8:
        return "aumentada"
    else:
        # Caso não corresponda a um tipo padrão (ex: tríade de sétima, ou intervalos inválidos)
        return "indefinida"
def classificar_triade(notas: list[str]) -> str:
    SEMITONE_MAP = {
        "C": 0, "B#": 0,
        "C#": 1, "Db": 1,
        "D": 2,
        "D#": 3, "Eb": 3,
        "E": 4,
        "F": 5, "E#": 5,
        "F#": 6, "Gb": 6,
        "G": 7,
        "G#": 8, "Ab": 8,
        "A": 9,
        "A#": 10, "Bb": 10,
        "B": 11
    }

    def get_semitone(note: str) -> int:
        if note in SEMITONE_MAP:
            return SEMITONE_MAP[note]
        raise ValueError(f"Nota desconhecida: {note}")

    try:
        s0 = get_semitone(notas[0])
        s1 = get_semitone(notas[1])
        s2 = get_semitone(notas[2])
    except ValueError as e:
        return "Erro de entrada"

    # Calcula os intervalos em semitons entre a nota raiz (s0) e as outras duas notas.
    intervalo1 = abs(s1 - s0)
    intervalo2 = abs(s2 - s0)

    # Ordena os dois intervalos para facilitar a comparação com padrões teóricos.
    intervals_calculados = sorted([intervalo1, intervalo2])

    # Padrões de intervalos (em semitons) do terceiro e do quinto grau em relação à tônica:
    # {3rd, 5th}
    PADROES = {
        (3, 7): "menor",  # Ex: C-Eb-G -> 3, 7
        (4, 7): "maior",   # Ex: C-E-G -> 4, 7
        (3, 6): "diminuta", # Ex: C-Eb-Gb -> 3, 6
        (4, 8): "aumentada"  # Ex: C-E-G# -> 4, 8
    }

    if intervals_calculados in PADROES:
        return PADROES[intervals_calculados]
    else:
        return "Não é uma tríade padrão (ou está fora do alcance de classificação)"
def classificar_triade(notas: list[str]) -> str:
    PITCH_MAP = {
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
        "B": 11,
    }

    def get_semitones(note: str) -> int:
        if note not in PITCH_MAP:
            raise ValueError(f"Nota desconhecida: {note}")
        return PITCH_MAP[note]

    root = notas[0]
    n2 = notas[1]
    n3 = notas[2]

    semitones_r = get_semitones(root)
    semitones_a = get_semitones(n2)
    semitones_b = get_semitones(n3)

    def calculate_interval(s1: int, s2: int) -> int:
        # Calcula a diferença de semitons e normaliza para o intervalo positivo em [0, 11]
        diff = abs(s1 - s2)
        return diff % 12

    d_a = calculate_interval(semitones_r, semitones_a)
    d_b = calculate_interval(semitones_r, semitones_b)

    intervals = sorted([d_a, d_b])
    i3 = intervals[0]
    i5 = intervals[1]

    # Comparação dos intervalos (Técnica: [Terça, Quinta])
    if i3 == 4 and i5 == 7:
        return "maior"
    elif i3 == 3 and i5 == 7:
        return "menor"
    elif i3 == 3 and i5 == 6:
        return "diminuta"
    elif i3 == 4 and i5 == 8:
        return "aumentada"
    else:
        # Caso não seja um dos tipos padrão (ex: segundos, ou intervalos inválidos)
        return "indefinida"
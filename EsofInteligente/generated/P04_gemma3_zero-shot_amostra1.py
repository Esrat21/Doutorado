def classificar_triade(notas: list[str]) -> str:
    NOTE_MAP = {
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

    def get_semitone(note: str) -> int:
        if note not in NOTE_MAP:
            raise ValueError(f"Nota desconhecida: {note}")
        return NOTE_MAP[note]

    try:
        root_semitones = get_semitone(notas[0])
        s1 = get_semitone(notas[1])
        s2 = get_semitone(notas[2])
    except ValueError as e:
        return "Erro de entrada"

    def calculate_interval(root: int, note: int) -> int:
        diff = abs(root - note)
        # Garante que o intervalo seja o menor caminho em semitons (dentro de uma oitava)
        return min(diff, 12 - diff)

    intervalos = []
    intervalo_a = calculate_interval(root_semitones, s1)
    intervalo_b = calculate_interval(root_semitones, s2)

    # Os dois intervalos que definem a tríade (Terça e Quinta)
    intervals_set = set([intervalo_a, intervalo_b])

    if len(intervals_set) != 2:
        return "Não forma uma tríade válida"

    sorted_intervals = sorted(list(intervals_set))
    third = sorted_intervals[0]
    fifth = sorted_intervals[1]

    # Classificação baseada nos intervalos (Terça, Quinta) em semitons
    if third == 4 and fifth == 7:
        return "maior"
    elif third == 3 and fifth == 7:
        return "menor"
    elif third == 3 and fifth == 6:
        return "diminuta"
    elif third == 4 and fifth == 8:
        return "aumentada"
    else:
        return "indefinida"
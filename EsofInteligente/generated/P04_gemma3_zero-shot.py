def classificar_triade(notas: list[str]) -> str:
    NOTE_MAP = {
        "C": 0, "B#": 0,
        "C#": 1, "Db": 1,
        "D": 2,
        "D#": 3, "Eb": 3,
        "E": 4,
        "F": 5,
        "F#": 6, "Gb": 6,
        "G": 7,
        "G#": 8, "Ab": 8,
        "A": 9,
        "A#": 10, "Bb": 10,
        "B": 11
    }

    def note_to_semitones(note: str) -> int:
        if note not in NOTE_MAP:
            raise ValueError(f"Nota desconhecida: {note}")
        return NOTE_MAP[note]

    try:
        # 1. Converter todas as notas para valores semitonais (0-11)
        semitones = [note_to_semitones(n) for n in notas]
    except ValueError as e:
        return str(e) # Trata erro de input

    # 2. Ordenar os intervalos para garantir que o cálculo seja consistente (Root, Third, Fifth)
    # Os intervalos são calculados em relação à menor nota (a raiz).
    semitones.sort()

    if len(semitones) < 3:
        return "Triade inválida" # Caso de segurança

    root = semitones[0]
    third_interval = semitones[1] - root
    fifth_interval = semitones[2] - root

    # 3. Classificar a tríade com base nos intervalos (em semitons)
    # Intervalos padrão:
    # Maior Terça = 4 semitons
    # Menor Terça = 3 semitons
    # Quinta Perfeita = 7 semitons
    # Diminuta Quarta/Quinta = 6 semitons
    # Aumentada Quinta = 8 semitons

    if third_interval == 4 and fifth_interval == 7:
        return "maior"
    elif third_interval == 3 and fifth_interval == 7:
        return "menor"
    elif third_interval == 3 and fifth_interval == 6:
        return "diminuta"
    elif third_interval == 4 and fifth_interval == 8:
        return "aumentada"
    else:
        # Caso de tríade não padrão (ex: sexta, ou intervalo incorreto)
        return "indefinida"
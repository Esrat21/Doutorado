def validar_escala_maior(notas: list[str]) -> bool:
    NOTE_MAP = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5,
        "F#": 6, "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }

    if len(notas) != 8:
        return False

    try:
        semitones = [NOTE_MAP[nota] for nota in notas]
    except KeyError:
        return False

    intervalos_calculados = []
    for i in range(7):
        # Calcula o intervalo em semitons entre a nota i e a nota i+1
        intervalo = (semitones[i+1] - semitones[i]) % 12
        intervalos_calculados.append(intervalo)

    PADRAO_ESCALA_MAIOR = [2, 2, 1, 2, 2, 2, 1]

    return intervalos_calculados == PADRAO_ESCALA_MAIOR
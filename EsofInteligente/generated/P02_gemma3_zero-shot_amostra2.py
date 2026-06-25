def validar_escala_maior(notas: list[str]) -> bool:
    chromatic_map = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5,
        "F#": 6, "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }
    target_intervals = [2, 2, 1, 2, 2, 2, 1]

    if len(notas) != 8:
        return False

    try:
        pitches = []
        for nota in notas:
            if nota not in chromatic_map:
                return False
            pitches.append(chromatic_map[nota])
    except Exception:
        return False

    intervals = []
    for i in range(len(pitches) - 1):
        interval = (pitches[i+1] - pitches[i]) % 12
        intervals.append(interval)

    return intervals == target_intervals
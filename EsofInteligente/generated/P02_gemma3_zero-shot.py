def validar_escala_maior(notas: list[str]) -> bool:
    CHROMATIC_SCALE = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    NOTE_TO_INDEX = {note: i for i, note in enumerate(CHROMATIC_SCALE)}
    TARGET_INTERVALS = [2, 2, 1, 2, 2, 2, 1]

    if len(notas) != 8:
        return False

    intervals = []
    for i in range(len(notas) - 1):
        current_note = notas[i]
        next_note = notas[i+1]

        if current_note not in NOTE_TO_INDEX or next_note not in NOTE_TO_INDEX:
            return False # Invalid note found

        current_index = NOTE_TO_INDEX[current_note]
        next_index = NOTE_TO_INDEX[next_note]

        # Calculate the interval (semitones) using modulo 12 for wrap-around
        interval = (next_index - current_index) % 12
        intervals.append(interval)

    return intervals == TARGET_INTERVALS
def validar_escala_maior(notas: list[str]) -> bool:
    chromatic_scale = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    note_to_int = {note: i for i, note in enumerate(chromatic_scale)}

    if len(notas) != 8:
        return False

    try:
        intervals = []
        for i in range(len(notas) - 1):
            n1_val = note_to_int[notas[i]]
            n2_val = note_to_int[notas[i+1]]
            interval = (n2_val - n1_val) % 12
            intervals.append(interval)

        required_intervals = [2, 2, 1, 2, 2, 2, 1]
        return intervals == required_intervals

    except KeyError:
        return False
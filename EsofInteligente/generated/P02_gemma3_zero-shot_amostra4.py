def validar_escala_maior(notas: list[str]) -> bool:
    chroma = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    note_to_int = {nota: i for i, nota in enumerate(chroma)}

    if len(notas) != 8:
        return False

    try:
        indices = [note_to_int[nota] for nota in notas]
    except KeyError:
        return False

    intervals = []
    for i in range(len(indices)):
        current_index = indices[i]
        next_index = indices[(i + 1) % len(indices)]
        interval = (next_index - current_index) % 12
        intervals.append(interval)

    # The required pattern is for the intervals between consecutive notes,
    # including the octave closure (last to first).
    # For an 8-note scale, there are 8 intervals.
    # Target: [2, 2, 1, 2, 2, 2, 1] + closing interval (which must be 0 or 12)

    # Since the input is a list of 8 notes representing an octave,
    # we check the first 7 intervals and assume the last one closes the octave.
    # The standard definition for checking scale structure uses the 7 internal steps.
    target_intervals = [2, 2, 1, 2, 2, 2, 1]

    calculated_internal_intervals = []
    for i in range(len(indices) - 1):
        interval = (indices[i+1] - indices[i]) % 12
        calculated_internal_intervals.append(interval)

    return calculated_internal_intervals == target_intervals
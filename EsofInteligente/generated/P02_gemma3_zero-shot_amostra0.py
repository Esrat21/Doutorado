def validar_escala_maior(notas: list[str]) -> bool:
    chromatic_scale = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    note_to_semitone = {note: i for i, note in enumerate(chromatic_scale)}

    if len(notas) != 8:
        return False

    try:
        semitones = [note_to_semitone[nota] for nota in notas]
    except KeyError:
        return False

    intervals = []
    for i in range(len(semitones) - 1):
        interval = (semitones[(i + 1) % len(semitones)] - semitones[i]) % 12
        if interval < 0:
            interval += 12
        intervals.append(interval)

    # The required pattern is for the intervals between consecutive notes, including the octave jump (the last to the first).
    # Since we are given 8 notes and checking if they form a scale, we check the 7 internal intervals + the interval from the 8th note back to the 1st.
    # However, standard definition of an "octave" implies the 8th note is the root octave higher than the 1st.
    # If we assume the input list represents the notes in order (N1, N2, ..., N8), and we only check the 7 intervals:
    required_intervals = [2, 2, 1, 2, 2, 2, 1]

    calculated_intervals = []
    for i in range(len(semitones) - 1):
        interval = (semitones[i + 1] - semitones[i]) % 12
        if interval < 0:
            interval += 12
        calculated_intervals.append(interval)

    return calculated_intervals == required_intervals
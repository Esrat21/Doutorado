def classificar_tetrade(notas: list[str]) -> str:
    note_map = {
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

    def get_pitch_class(note: str) -> int:
        if note.upper() in note_map:
            return note_map[note.upper()]
        # Fallback for robustness if input format is unexpected
        raise ValueError(f"Nota desconhecida: {note}")

    try:
        root = get_pitch_class(notas[0])
        pitches = [get_pitch_class(n) for n in notas]
    except ValueError:
        return "Erro de entrada" # Handle invalid notes gracefully if necessary

    # Calculate the three intervals relative to the root (R, P2-R, P3-R, P4-R)
    intervals = []
    for i in range(1, 4):
        interval = abs(pitches[i] - root) % 12
        if interval > 0: # Ensure we don't count the octave of the root itself
            intervals.append(interval)

    # Sort intervals for consistent comparison (e.g., [3, 4, 7] instead of [7, 3, 4])
    intervals.sort()

    # Define expected interval patterns relative to the root:
    # M7: Major Third (4), Perfect Fifth (7), Major Seventh (11) -> [4, 7, 11]
    if intervals == [4, 7, 11]:
        return "maior7"

    # D7: Major Third (4), Perfect Fifth (7), Minor Seventh (10) -> [4, 7, 10]
    elif intervals == [4, 7, 10]:
        return "dominante7"

    # m7: Minor Third (3), Perfect Fifth (7), Minor Seventh (10) -> [3, 7, 10]
    elif intervals == [3, 7, 10]:
        return "menor7"

    # meio_diminuto (m7b5): Minor Third (3), Diminished Fifth (6), Minor Seventh (9) -> [3, 6, 9]
    elif intervals == [3, 6, 9]:
        return "meio_diminuto"

    # If none of the standard patterns match
    return "Tipo desconhecido"<unused56>
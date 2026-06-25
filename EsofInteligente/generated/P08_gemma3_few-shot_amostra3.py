def classificar_tetrade(notas: list[str]) -> str:
    note_map = {
        "C": 0, "C#": 1, "Db": 2, "D": 2,
        "D#": 3, "Eb": 3, "E": 4, "Fb": 4,
        "F": 5, "F#": 6, "Gb": 6, "G": 7,
        "G#": 8, "Ab": 8, "A": 9, "A#": 10,
        "Bb": 10, "B": 11
    }

    # Normalize notes to ensure consistent mapping (e.g., Db vs C#, Eb vs D#)
    normalized_notes = []
    for note in notas:
        if len(note) == 2 and note[1] == '#':
            normalized_notes.append(note)
        elif len(note) == 2 and note[1] == 'b':
            # Handle flats by converting them to the standard enharmonic equivalent if possible,
            # but for simplicity in this context, we rely on the provided map keys.
            if note[:-1].lower() + "b" not in note_map:
                 # Fallback or error handling might be needed, but assuming valid input based on common theory names
                pass
            normalized_notes.append(note)

    # Map notes to semitone values (pitch classes)
    try:
        pitches = [note_map[n] for n in normalized_notes]
    except KeyError:
        return "Erro: Nota inválida fornecida."

    # Sort the pitches to ensure consistent interval calculation relative to the root
    sorted_pitches = sorted(list(set(pitches))) # Use set to handle duplicate notes, then sort.

    if len(sorted_pitches) < 4:
        return "Erro: Não é uma tétrade completa."

    # Calculate intervals relative to the lowest note (the root)
    root = sorted_pitches[0]
    intervals = tuple(sorted([p - root for p in sorted_pitches]))

    # Define expected interval sets (semitones from root)
    patterns = {
        tuple(sorted([0, 4, 7, 11])): "maior7",      # R, M3, P5, M7
        tuple(sorted([0, 4, 7, 10])): "dominante7",   # R, M3, P5, m7
        tuple(sorted([0, 3, 7, 10])): "menor7",      # R, m3, P5, m7
        tuple(sorted([0, 3, 6, 10])): "meio_diminuto" # R, m3, d5, m7
    }

    if intervals in patterns:
        return patterns[intervals]
    else:
        # Fallback for chords that don't fit the specified types (e.g., major triad)
        return "Tipo de tétrade não identificado."
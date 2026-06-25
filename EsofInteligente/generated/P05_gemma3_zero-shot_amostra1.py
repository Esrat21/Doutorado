def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    PITCHES = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

    def get_pitch_index(note: str) -> int:
        if note.lower() in ["c", "cs"]: return 0
        if note.lower() in ["c#", "db"]: return 1
        if note.lower() in ["d", "d#", "eb"]: return 2
        if note.lower() in ["d#", "e"], ["eb", "f"]: return 3
        if note.lower() in ["e", "f", "fb"]: return 4
        if note.lower() in ["f", "g", "gb"]: return 5
        if note.lower() in ["f#", "g#", "ab"]: return 6
        if note.lower() in ["g", "a", "ab"]: return 7
        if note.lower() in ["g#", "a#", "bb"]: return 8
        if note.lower() in ["a", "b", "bb"]: return 9
        if note.lower() in ["a#", "c", "cb"]: return 10
        if note.lower() in ["b", "d", "cb"]: return 11
        raise ValueError("Tonalidade inválida.")

    def get_note(index: int) -> str:
        return PITCHES[index % 12]

    try:
        root_index = get_pitch_index(tonalidade.upper())
    except ValueError:
        return []

    # Semitone steps for the major scale degrees (I, ii, iii, IV, V, vi, vii)
    scale_degrees_semitones = [0, 2, 4, 5, 7, 9, 11] # Intervals from root: T, T+2, T+4, T+5, T+7, T+9, T+11

    # Chord quality definitions (relative to the degree's root)
    # I: Major (M3, P5), ii: Minor (m3, P5), iii: Minor (m3, P5), IV: Major (M3, P5), V: Major (M3, P5), vi: Minor (m3, P5), vii: Diminished (dim3, dim5)
    chord_qualities = [
        ("maj", 4, 7),  # I: M3, P5
        ("min", 3, 7),  # ii: m3, P5
        ("min", 3, 7),  # iii: m3, P5
        ("maj", 4, 7),  # IV: M3, P5
        ("maj", 4, 7),  # V: M3, P5
        ("min", 3, 7),  # vi: m3, P5
        ("dim", 3, 6)   # vii: dim3, dim5 (Note: The interval for the fifth is usually calculated as root + 11 semitones from the scale degree, but since we are using relative intervals based on the degree's root, and the seventh degree is diminished, its perfect fifth is often treated as a tritone/diminished fifth. For simplicity in this model, we use the standard interval structure for vii: dim3, dim5)
    ]

    campo_harmonico = []

    for i in range(7):
        # Calculate the root note of the chord (the degree itself)
        root_index_chord = (root_index + scale_degrees_semitones[i]) % 12
        root_note = get_note(root_index_chord)

        quality, _, _ = chord_qualities[i]

        if quality == "maj":
            cifra = f"{root_note}maj"
        elif quality == "min":
            cifra = f"{root_note}m"
        elif quality == "dim":
            cifra = f"{root_note}dim"
        else:
            # Should not happen based on the pattern
            cifra = root_note

        campo_harmonico.append(cifra)

    return campo_harmonico
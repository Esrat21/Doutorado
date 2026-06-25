def classificar_tetrade(notas: list[str]) -> str:
    NOTE_TO_SEMITONES = {
        "C": 0, "B#": 0, # B# is enharmonically C, but we treat it as a distinct input if needed. Assuming standard names for simplicity.
        "C#": 1, "Db": 1,
        "D": 2, "D#": 3, "Eb": 3,
        "E": 4, "F": 5, "F#": 6, "Gb": 6,
        "G": 7, "G#": 8, "Ab": 8,
        "A": 9, "A#": 10, "Bb": 10,
        "B": 11,
    }

    # Handle common enharmonic spellings for robustness (e.g., Db vs C#, Eb vs D#)
    def get_semitone(note: str) -> int:
        if note in ("Db", "C#"): return 1
        if note in ("Eb", "D#"): return 3
        if note in ("Gb", "F#"): return 6
        if note in ("Ab", "G#"): return 8
        if note in ("Bb", "A#"): return 10
        return NOTE_TO_SEMITONES.get(note, -1)

    try:
        semitones = [get_semitone(n) for n in notas]
    except Exception:
        return "erro" # Should not happen if input is clean

    if len(semitones) != 4 or any(s == -1 for s in semitones):
        # Handle invalid input length or unknown notes
        return "entrada invalida"

    root_semitone = semitones[0]

    # Calculate the intervals (in semitones, modulo 12) relative to the root.
    intervals = set()
    for i in range(4):
        interval = (semitones[i] - root_semitone) % 12
        intervals.add(interval)

    # The interval set must contain {0, I2, I3, I4} where I are the non-root intervals.
    # We sort them for consistent comparison.
    sorted_intervals = sorted(list(intervals))

    # Define standard chord structures (semitones from root)
    CHORD_PATTERNS = {
        "maior7": {0, 4, 7, 11},  # Root, M3, P5, M7
        "dominante7": {0, 4, 7, 10}, # Root, M3, P5, m7
        "menor7": {0, 3, 7, 10},   # Root, m3, P5, m7
        "meio_diminuto": {0, 3, 6, 10} # Root, m3, d5, m7
    }

    # Check which pattern matches the calculated set of intervals
    for chord_type, required_intervals in CHORD_PATTERNS.items():
        if required_intervals == intervals:
            return chord_type

    # If no standard pattern is matched
    return "indefinido"<unused56>
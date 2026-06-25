def classificar_tetrade(notas: list[str]) -> str:
    # Assume that the input strings can be converted to integer semitone values.
    # We calculate the intervals relative to the root (the first note).
    try:
        root = int(notas[0])
        intervals = []
        for i in range(1, 4):
            interval = int(notas[i]) - root
            intervals.append(abs(interval))

        # Sort and ensure uniqueness of the intervals (excluding 0)
        intervals_set = sorted(list(set(intervals)))

    except ValueError:
        # Handle case where input strings are not valid integers (semitones)
        return "Erro de entrada"

    if len(intervals_set) != 3:
        # This handles cases where the notes might be redundant or invalid for a standard tetrade.
        pass # We proceed with comparison using available intervals

    # Define the required interval sets (semitones from root):
    # {M3, P5, M7} -> [4, 7, 11]
    MAJOR_7 = {4, 7, 11}
    # {m3, P5, m7} -> [3, 7, 10]
    MINOR_7 = {3, 7, 10}
    # {M3, P5, m7} -> [4, 7, 10]
    DOMINANT_7 = {4, 7, 10}
    # {m3, d5, m7} -> [3, 6, 10]
    HALF_DIMINISHED = {3, 6, 10}

    current_intervals = set(intervals_set)

    if current_intervals == MAJOR_7:
        return "maior7"
    elif current_intervals == MINOR_7:
        return "menor7"
    elif current_intervals == DOMINANT_7:
        return "dominante7"
    elif current_intervals == HALF_DIMINISHED:
        return "meio_diminuto"
    else:
        # Fallback for non-standard or ambiguous chords
        return "indefinido"
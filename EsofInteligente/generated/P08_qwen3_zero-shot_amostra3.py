def classificar_tetrade(notas: list[str]) -> str:
    notas_map = {
        'C': 0, 'C#': 1, 'Db': 1, 'D': 2, 'D#': 3, 'Eb': 3, 'E': 4,
        'F': 5, 'F#': 6, 'Gb': 6, 'G': 7, 'G#': 8, 'Ab': 8, 'A': 9,
        'A#': 10, 'Bb': 10, 'B': 11, 'Cb': 0, 'E#': 4, 'Fb': 5, 'B#': 11
    }
    semitons = [notas_map[n] for n in notas]
    intervals = [(s - semitons[0]) % 12 for s in semitons[1:]]
    
    if intervals == [4, 7, 11]:
        return 'maior7'
    elif intervals == [4, 7, 10]:
        return 'dominante7'
    elif intervals
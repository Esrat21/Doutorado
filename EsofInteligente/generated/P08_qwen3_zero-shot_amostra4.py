def classificar_tetrade(notas: list[str]) -> str:
    notas_map = {
        'C': 0, 'Db': 1, 'D': 2, 'Eb': 3, 'E': 4, 'F': 5,
        'Gb': 6, 'G': 7, 'Ab': 8, 'A': 9, 'Bb': 10, 'B': 11,
        'C#': 1, 'D#': 3, 'F#': 6, 'G#': 8, 'A#': 10
    }
    pitches = [notas_map[n.upper()] for n in notas]
    intervals = [(p
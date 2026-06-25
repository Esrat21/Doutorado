def classificar_triade(notas: list[str]) -> str:
    notas_para_semitons = {
        'C': 0, 'C#': 1, 'Db': 1, 'D': 2, 'D#': 3, 'Eb': 3,
        'E': 4, 'F': 5, 'F#': 6, 'Gb': 6, 'G': 7, 'G#': 8,
        'Ab': 8, 'A': 9, 'A#': 10, 'Bb': 10, 'B': 11
    }
    semitons = [notas_para_semitons[n] for n in notas]
    int1 = (semitons[1] - semitons[0]) % 12
    int2 = (semitons[2] - semitons[0]) % 12
    if int1 ==
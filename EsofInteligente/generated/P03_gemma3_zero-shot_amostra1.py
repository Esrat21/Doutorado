def cifra_para_semitom(nota: str) -> int:
    mapa_notas = {
        "C": 0, "D": 2, "E": 4, "F": 5, "G": 7, "A": 9, "B": 11,
        "C#": 1, "D#": 3, "F#": 6, "G#": 8, "A#": 10
    }
    return mapa_notas[nota]
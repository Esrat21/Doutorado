def cifra_para_semitom(nota: str) -> int:
    mapa_cifras = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5, "F#": 6,
        "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }
    return mapa_cifras[nota]
CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def cifra_para_semitom(nota: str) -> int:
    return CROMATICA.index(nota)
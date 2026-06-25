ESCALA_CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def calcular_intervalo(nota1: str, nota2: str) -> int:
    idx1 = ESCALA_CROMATICA.index(nota1)
    idx2 = ESCALA_CROMATICA.index(nota2)
    return (idx2 - idx1) % 12
CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False
    padrao = [2, 2, 1, 2, 2, 2, 1]
    intervalos = [(CROMATICA.index(notas[i+1]) - CROMATICA.index(notas[i])) % 12 for i in range(7)]
    return intervalos == padrao
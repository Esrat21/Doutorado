CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
PADRAO = [2, 2, 1, 2, 2, 2, 1]

def validar_escala_maior(notas: list[str]) -> bool:
    for i in range(7):
        if (CROMATICA.index(notas[i+1]) - CROMATICA.index(notas[i])) % 12 != PADRAO[i]:
            return False
    return True
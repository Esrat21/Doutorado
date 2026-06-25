CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
PADRAO_INTERVALOS = [2, 2, 1, 2, 2, 2, 1]

def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False
    for i in range(7):
        intervalo = (CROMATICA.index(notas[i+1]) - CROMATICA.index(notas[i])) % 12
        if intervalo != PADRAO_INTERVALOS[i]:
            return False
    return True
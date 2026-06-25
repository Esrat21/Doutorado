CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def classificar_triade(notas: list[str]) -> str:
    idx1 = CROMATICA.index(notas[0])
    idx2 = CROMATICA.index(notas[1])
    idx3 = CROMATICA.index(notas[2])
    int1 = (idx2 - idx1) % 12
    int2 = (idx3 - idx1) % 12
    if int1 == 4 and int2 == 7:
        return "maior"
    if int1 == 3 and int2 == 7:
        return "menor"
    if int1 == 3 and int2 == 6:
        return "diminuta"
    if int1 == 4 and int2 == 8:
        return "aumentada"
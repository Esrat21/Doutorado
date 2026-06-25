CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def classificar_triade(notas: list[str]) -> str:
    idx_root = CROMATICA.index(notas[0])
    int1 = (CROMATICA.index(notas[1]) - idx_root) % 12
    int2 = (CROMATICA.index(notas[2]) - idx_root) % 12
    if int1 == 4 and int2 == 7:
        return "maior"
    elif int1 == 3 and int2 == 7:
        return "menor"
    elif int1 == 3 and int2 == 6:
        return "diminuta"
    elif int1 == 4 and int2 == 8:
        return "aumentada"
    return "desconhecida"
CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def classificar_triade(notas: list[str]) -> str:
    idx0 = CROMATICA.index(notas[0])
    idx1 = CROMATICA.index(notas[1])
    idx2 = CROMATICA.index(notas[2])
    
    int1 = (idx1 - idx0) % 12
    int2 = (idx2 - idx0) % 12
    
    if int1 == 4 and int2 == 7:
        return "maior"
    elif int1 == 3 and int2 == 7:
        return "menor"
    elif int1 == 3 and int2 == 6:
        return "diminuta"
    elif int1 == 4 and int2 == 8:
        return "aumentada"
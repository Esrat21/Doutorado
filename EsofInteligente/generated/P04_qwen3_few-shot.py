CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def classificar_triade(notas: list[str]) -> str:
    idx_root = CROMATICA.index(notas[0])
    int2 = (CROMATICA.index(notas[1]) - idx_root) % 12
    int3 = (CROMATICA.index(notas[2]) - idx_root) % 12

    if int2 == 4 and int3 == 7:
        return "maior"
    elif int2 == 3 and int3 == 7:
        return "menor"
    elif int2 == 3 and int3 == 6:
        return "diminuta"
    elif int2 == 4 and int3 == 8:
        return "aumentada"
def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False
    cromatica = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    padrao = [2, 2, 1, 2, 2, 2, 1]
    for i in range(7):
        idx1 = cromatica.index(notas[i])
        idx2 = cromatica.index(notas[i+1])
        if (idx2 - idx1) % 12 != padrao[i]:
            return False
    return True
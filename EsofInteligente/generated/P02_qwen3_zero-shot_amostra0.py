def validar_escala_maior(notas: list[str]) -> bool:
    escala_cromatica = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    if len(notas) != 8:
        return False
    try:
        indices = [escala_cromatica.index(n.upper()) for n in notas]
    except ValueError:
        return False
    padrao = [2, 2, 1, 2, 2, 2, 1]
    for i in range(7):
        if (indices[i+1] - indices[i]) % 12 != padrao[i]:
            return False
    return True
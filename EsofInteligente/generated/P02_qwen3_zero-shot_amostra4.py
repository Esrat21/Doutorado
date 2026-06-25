def validar_escala_maior(notas: list[str]) -> bool:
    cromatica = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']
    if len(notas) != 8 or not all(n in cromatica for n in notas):
        return False
    indices = [cromatica.index(n) for n in notas]
    expected = [2, 2, 1, 2, 2, 2, 1]
    for i in range(7):
        if (indices[i+1] - indices[i]) % 12 != expected[i]:
            return False
    return True
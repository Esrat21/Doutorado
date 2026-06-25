CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False

    intervalos_esperados = [2, 2, 1, 2, 2, 2, 1]

    for i in range(7):
        nota_inicial = notas[i]
        nota_final = notas[i+1]

        try:
            idx_inicial = CROMATICA.index(nota_inicial)
            idx_final = CROMATICA.index(nota_final)
        except ValueError:
            return False # Nota inválida na escala cromática

        intervalo_calculado = (idx_final - idx_inicial) % 12

        if intervalo_calculado != intervalos_esperados[i]:
            return False

    return True
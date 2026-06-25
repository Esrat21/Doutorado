CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False

    intervalos_esperados = [2, 2, 1, 2, 2, 2, 1]

    for i in range(7):
        nota_atual = notas[i]
        proxima_nota = notas[i+1]

        try:
            idx_atual = CROMATICA.index(nota_atual)
            idx_proxima = CROMATICA.index(proxima_nota)
        except ValueError:
            return False # Nota inválida na escala cromática

        intervalo_calculado = (idx_proxima - idx_atual) % 12

        if intervalo_calculado != intervalos_esperados[i]:
            return False

    return True
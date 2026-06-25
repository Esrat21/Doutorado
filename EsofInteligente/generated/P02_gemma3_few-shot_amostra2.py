CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False

    intervalos_esperados = [2, 2, 1, 2, 2, 2, 1]
    intervalos_calculados = []

    for i in range(7):
        nota1 = notas[i]
        nota2 = notas[i+1]

        if nota1 not in CROMATICA or nota2 not in CROMATICA:
            return False

        i1 = CROMATICA.index(nota1)
        i2 = CROMATICA.index(nota2)
        intervalo = (i2 - i1) % 12
        intervalos_calculados.append(intervalo)

    return intervalos_calculados == intervalos_esperados
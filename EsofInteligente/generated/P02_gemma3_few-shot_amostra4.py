CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False

    intervalos_esperados = [2, 2, 1, 2, 2, 2, 1]
    intervalos_calculados = []

    for i in range(7):
        nota1 = notas[i]
        nota2 = notas[i+1]

        try:
            idx1 = CROMATICA.index(nota1)
            idx2 = CROMATICA.index(nota2)
        except ValueError:
            return False # Nota inválida encontrada

        intervalo = (idx2 - idx1) % 12
        intervalos_calculados.append(intervalo)

    return intervalos_calculados == intervalos_esperados
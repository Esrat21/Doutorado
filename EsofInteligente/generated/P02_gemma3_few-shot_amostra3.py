CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False

    intervalos = []
    for i in range(len(notas) - 1):
        nota1 = notas[i]
        nota2 = notas[i+1]
        try:
            i1 = CROMATICA.index(nota1)
            i2 = CROMATICA.index(nota2)
            intervalo = (i2 - i1) % 12
            intervalos.append(intervalo)
        except ValueError:
            return False

    escala_maior_padrao = [2, 2, 1, 2, 2, 2, 1]
    return intervalos == escala_maior_padrao
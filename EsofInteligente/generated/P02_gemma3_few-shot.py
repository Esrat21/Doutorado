CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def validar_escala_maior(notas: list[str]) -> bool:
    if len(notas) != 8:
        return False

    intervalos_esperados = [2, 2, 1, 2, 2, 2, 1]
    intervalos_calculados = []

    for i in range(len(notas) - 1):
        nota_atual = notas[i]
        proxima_nota = notas[i+1]

        try:
            idx_atual = CROMATICA.index(nota_atual)
            idx_proxima = CROMATICA.index(proxima_nota)
        except ValueError:
            return False # Nota inválida na lista

        intervalo = (idx_proxima - idx_atual) % 12
        intervalos_calculados.append(intervalo)

    return intervalos_calculados == intervalos_esperados
CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _get_index(tonalidade: str) -> int:
    return CROMATICA.index(tonalidade)


def _nota_por_intervalo(root_index: int, intervalo: int) -> str:
    new_index = (root_index + intervalo) % 12
    return CROMATICA[new_index]


def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    root_index = _get_index(tonalidade)

    # Intervalos semitons para os graus diatônicos (I, ii, iii, IV, V, vi, vii)
    intervalos = [0, 2, 4, 5, 7, 9, 11]

    roots = []
    for i in intervalos:
        root_note = _nota_por_intervalo(root_index, i)
        roots.append(root_note)

    # Qualidades dos acordes (I=Maior, ii=Menor, iii=Menor, IV=Maior, V=Maior, vi=Menor, vii=Diminuto)
    qualidades = ["", "m", "m", "", "", "m", "dim"]

    campo_harmonico = []
    for i in range(7):
        root = roots[i]
        quality = qualidades[i]
        if quality:
            campo_harmonico.append(f"{root}{quality}")
        else:
            campo_harmonico.append(root)

    return campo_harmonico
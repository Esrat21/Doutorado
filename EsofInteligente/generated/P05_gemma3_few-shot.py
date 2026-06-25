CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _nota(root: str, semitons: int) -> str:
    start_index = CROMATICA.index(root)
    new_index = (start_index + semitones) % 12
    return CROMATICA[new_index]


def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    # Intervalos em semitons para os 7 graus diatônicos de uma escala maior (I, ii, iii, IV, V, vi, vii°)
    intervalos_raizes = [0, 2, 4, 5, 7, 9, 11]

    # Qualidades dos acordes correspondentes aos intervalos acima
    qualidades = ["maior", "menor", "menor", "maior", "maior", "menor", "diminuto"]

    campo_harmonico = []

    for i in range(7):
        raiz = _nota(tonalidade, intervalos_raizes[i])
        qualidade = qualidades[i]
        acorde = f"{raiz} {qualidade}"
        campo_harmonico.append(acorde)

    return campo_harmonico
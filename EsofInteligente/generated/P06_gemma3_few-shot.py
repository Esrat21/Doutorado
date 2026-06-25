CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _intervalo(n1, n2):
    return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


def detectar_inversao(notas: list[str]) -> str:
    fundamental = notas[0]
    terca = notas[1]
    quinta = notas[2]

    i_f = CROMATICA.index(fundamental)
    i_t = CROMATICA.index(terca)
    i_p = CROMATICA.index(quinta)

    min_intervalo = min(i_f, i_t, i_p)

    if min_intervalo == i_f:
        return "estado fundamental"
    elif min_intervalo == i_t:
        return "primeira inversao"
    else: # Deve ser o quinto
        return "segunda inversao"
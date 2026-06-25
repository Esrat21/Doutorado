CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _intervalo(n1, n2):
    return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


def detectar_inversao(notas: list[str]) -> str:
    fundamental, terca, quinta = notas

    idx_f = CROMATICA.index(fundamental)
    idx_t = CROMATICA.index(terca)
    idx_p = CROMATICA.index(quinta)

    min_idx = min(idx_f, idx_t, idx_p)

    if min_idx == idx_f:
        return "estado fundamental"
    elif min_idx == idx_t:
        return "primeira inversao"
    elif min_idx == idx_p:
        return "segunda inversao"
    else:
        # Este caso não deve ocorrer se as notas forem válidas e distintas
        return "inversao desconhecida"
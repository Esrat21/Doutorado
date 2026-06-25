CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _intervalo(n1, n2):
    return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


def detectar_inversao(notas: list[str]) -> str:
    fundamental, terca, quinta = notas

    idx_f = CROMATICA.index(fundamental)
    idx_t = CROMATICA.index(terca)
    idx_q = CROMATICA.index(quinta)

    indices = [idx_f, idx_t, idx_q]
    min_pitch_index = min(indices)

    if min_pitch_index == idx_f:
        return "estado fundamental"
    elif min_pitch_index == idx_t:
        return "primeira inversao"
    elif min_pitch_index == idx_q:
        return "segunda inversao"
    else:
        # Caso de empate ou erro na entrada (não deve ocorrer com 3 notas distintas)
        return "inversao indeterminada"
CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _intervalo(n1, n2):
    return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


def detectar_inversao(notas: list[str]) -> str:
    fundamental_pitch = notas[0]
    third_pitch = notas[1]
    fifth_pitch = notas[2]

    idx_f = CROMATICA.index(fundamental_pitch)
    idx_t = CROMATICA.index(third_pitch)
    idx_p = CROMATICA.index(fifth_pitch)

    min_index = min(idx_f, idx_t, idx_p)

    if min_index == idx_f:
        return 'estado fundamental'
    elif min_index == idx_t:
        return 'primeira inversao'
    else:
        return 'segunda inversao'
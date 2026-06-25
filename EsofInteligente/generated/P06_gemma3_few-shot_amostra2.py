CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _intervalo(n1, n2):
    return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


def detectar_inversao(notas: list[str]) -> str:
    fundamental, terca, quinta = notas
    pitch_f = CROMATICA.index(fundamental)
    pitch_t = CROMATICA.index(terca)
    pitch_p = CROMATICA.index(quinta)

    min_pitch = min(pitch_f, pitch_t, pitch_p)

    if min_pitch == pitch_f:
        return "estado fundamental"
    elif min_pitch == pitch_t:
        return "primeira inversao"
    elif min_pitch == pitch_p:
        return "segunda inversao"
    else:
        # Caso de empate ou erro (não deve ocorrer com 3 notas distintas)
        return "inversao indeterminada"
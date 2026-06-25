CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _intervalo(n1, n2):
    return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


def detectar_inversao(notas: list[str]) -> str:
    fundamental, terca, quinta = notas

    indices = {
        "F": CROMATICA.index(fundamental),
        "T": CROMATICA.index(terca),
        "P": CROMATICA.index(quinta)
    }

    min_pitch = min(indices["F"], indices["T"], indices["P"])

    if min_pitch == indices["F"]:
        return "estado fundamental"
    elif min_pitch == indices["T"]:
        return "primeira inversao"
    elif min_pitch == indices["P"]:
        return "segunda inversao"
    else:
        # Este caso não deve ocorrer se as notas forem válidas e distintas
        return "erro de detecção"
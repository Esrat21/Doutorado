CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    try:
        root_index = CROMATICA.index(tonalidade)
    except ValueError:
        return []

    roots = [
        CROMATICA[(root_index + 0) % 12],  # I
        CROMATICA[(root_index + 2) % 12],  # ii
        CROMATICA[(root_index + 4) % 12],  # iii
        CROMATICA[(root_index + 5) % 12],  # IV
        CROMATICA[(root_index + 7) % 12],  # V
        CROMATICA[(root_index + 9) % 12],  # vi
        CROMATICA[(root_index + 11) % 12]  # vii
    ]

    campo = []
    # I: Major
    campo.append(roots[0] + "maj")
    # ii: Minor
    campo.append(roots[1] + "min")
    # iii: Minor
    campo.append(roots[2] + "min")
    # IV: Major
    campo.append(roots[3] + "maj")
    # V: Major
    campo.append(roots[4] + "maj")
    # vi: Minor
    campo.append(roots[5] + "min")
    # vii: Diminished
    campo.append(roots[6] + "dim")

    return campo
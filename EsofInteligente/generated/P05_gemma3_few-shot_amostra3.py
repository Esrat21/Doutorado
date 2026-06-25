CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _get_note(root: str, semitones: int) -> str:
    root_index = CROMATICA.index(root)
    new_index = (root_index + semitones) % 12
    return CROMATICA[new_index]


def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    roots = []
    # I: Major (0 semitones offset)
    roots.append(_get_note(tonalidade, 0))
    # ii: minor (2 semitones offset)
    roots.append(_get_note(tonalidade, 2))
    # iii: minor (4 semitones offset)
    roots.append(_get_note(tonalidade, 4))
    # IV: Major (5 semitones offset)
    roots.append(_get_note(tonalidade, 5))
    # V: Major (7 semitones offset)
    roots.append(_get_note(tonalidade, 7))
    # vi: minor (9 semitones offset)
    roots.append(_get_note(tonalidade, 9))
    # vii: diminished (11 semitones offset)
    roots.append(_get_note(tonalidade, 11))

    campo = []
    # I maior
    campo.append(f"{roots[0]}maj")
    # ii menor
    campo.append(f"{roots[1]}min")
    # iii menor
    campo.append(f"{roots[2]}min")
    # IV maior
    campo.append(f"{roots[3]}maj")
    # V maior
    campo.append(f"{roots[4]}maj")
    # vi menor
    campo.append(f"{roots[5]}min")
    # vii diminuto
    campo.append(f"{roots[6]}dim")

    return campo
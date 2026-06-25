CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _get_note(root_index: int, semitones: int) -> str:
    return CROMATICA[(root_index + semitones) % 12]


def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    try:
        root_index = CROMATICA.index(tonalidade)
    except ValueError:
        return ["Tonalidade inválida"]

    # Define os offsets dos graus da escala maior (em semitons do tom)
    offsets = [0, 2, 4, 5, 7, 9, 11]

    campo_harmonico = []

    for i in range(7):
        degree_offset = offsets[i]
        root_note = _get_note(root_index, degree_offset)

        if i == 0 or i == 3 or i == 4:  # I, IV, V (Maior)
            terca = _get_note(root_index, degree_offset + 4)
            quinta = _get_note(root_index, degree_offset + 7)
            acorde = f"{root_note}{terca}{quinta}"
        elif i == 1 or i == 2 or i == 5:  # ii, iii, vi (Menor)
            terca = _get_note(root_index, degree_offset + 3)
            quinta = _get_note(root_index, degree_offset + 7)
            acorde = f"{root_note}{terca}{quinta}"
        elif i == 6:  # vii (Diminuto)
            terca = _get_note(root_index, degree_offset + 3)
            quinta = _get_note(root_index, degree_offset + 6)
            acorde = f"{root_note}{terca}{quinta}"

        # Formata o nome do acorde para a saída desejada (ex: Cmaj, Dm, etc.)
        if i == 0:
            nome = "I maior"
        elif i == 1:
            nome = "ii menor"
        elif i == 2:
            nome = "iii menor"
        elif i == 3:
            nome = "IV maior"
        elif i == 4:
            nome = "V maior"
        elif i == 5:
            nome = "vi menor"
        else: # i == 6
            nome = "vii diminuto"

        campo_harmonico.append(f"{nome} ({acorde})")

    return campo_harmonico
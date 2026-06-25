CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _indice_nota(tonalidade: str) -> int:
    return CROMATICA.index(tonalidade)


def gerar_escala_maior(tonalidade: str) -> list[str]:
    root_index = _indice_nota(tonalidade)
    indices = [
        root_index,
        (root_index + 2) % 12,
        (root_index + 4) % 12,
        (root_index + 5) % 12,
        (root_index + 7) % 12,
        (root_index + 9) % 12,
        (root_index + 11) % 12
    ]
    return [_nota_por_indice(i) for i in indices]


def _nota_por_indice(indice: int) -> str:
    return CROMATICA[indice]


def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    escala = gerar_escala_maior(tonalidade)

    # I (Maior), ii (Menor), iii (Menor), IV (Maior), V (Maior), vi (Menor), vii° (Diminuto)
    acordes = [
        escala[0],  # I
        escala[1],  # ii
        escala[2],  # iii
        escala[3],  # IV
        escala[4],  # V
        escala[5],  # vi
        escala[6]   # vii°
    ]

    return acordes
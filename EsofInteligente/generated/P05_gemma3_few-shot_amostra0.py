CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _get_note_index(tonalidade: str) -> int:
    try:
        return CROMATICA.index(tonalidade)
    except ValueError:
        raise ValueError("Tonalidade inválida. Use notas em sharps (ex: 'C', 'G#').")


def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    start_index = _get_note_index(tonalidade)

    # Intervalos em semitons para os raízes dos acordes (I, ii, iii, IV, V, vi, vii°)
    # 0, 2, 4, 5, 7, 9, 11
    intervalos_raizes = [0, 2, 4, 5, 7, 9, 11]

    # Qualidades dos acordes (I: Maj, ii: min, iii: min, IV: Maj, V: Maj, vi: min, vii: dim)
    qualidades = ["maior", "menor", "menor", "maior", "maior", "menor", "diminuto"]

    acordes = []
    for i in range(7):
        # Calcula o índice da nota raiz no círculo cromático (modulo 12)
        root_index = (start_index + intervalos_raizes[i]) % 12
        root_note = CROMATICA[root_index]
        quality = qualidades[i]
        acordes.append(f"{root_note} {quality}")

    return acordes
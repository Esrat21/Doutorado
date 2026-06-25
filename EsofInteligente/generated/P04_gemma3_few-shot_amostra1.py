CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def classificar_triade(notas: list[str]) -> str:
    if len(notas) != 3:
        return "Triade inválida"

    try:
        idx_root = CROMATICA.index(notas[0])
        idx_third = CROMATICA.index(notas[1])
        idx_fifth = CROMATICA.index(notas[2])
    except ValueError:
        return "Nota inválida"

    # Calcula os intervalos em semitons a partir da tônica (root)
    intervalo_terca = (idx_third - idx_root) % 12
    intervalo_quinta = (idx_fifth - idx_root) % 12

    if intervalo_terca == 4 and intervalo_quinta == 7:
        return "maior"
    elif intervalo_terca == 3 and intervalo_quinta == 7:
        return "menor"
    elif intervalo_terca == 3 and intervalo_quinta == 6:
        return "diminuta"
    elif intervalo_terca == 4 and intervalo_quinta == 8:
        return "aumentada"
    else:
        # Caso não caia em nenhum padrão clássico (ex: aumentativa, etc.)
        return "Não classificada"
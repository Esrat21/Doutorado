CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def classificar_triade(notas: list[str]) -> str:
    if len(set(notas)) != 3:
        return "Triade inválida"

    indices = [CROMATICA.index(n) for n in notas]
    indices.sort()

    # Intervalo da Terça (Root -> Third)
    intervalo_terca = indices[1] - indices[0]
    # Intervalo da Quinta (Root -> Fifth)
    intervalo_quinta = indices[2] - indices[0]

    if intervalo_terca == 4 and intervalo_quinta == 7:
        return "maior"
    elif intervalo_terca == 3 and intervalo_quinta == 7:
        return "menor"
    elif intervalo_terca == 2 and intervalo_quinta == 3:
        return "diminuta"
    elif intervalo_terca == 4 and intervalo_quinta == 8:
        return "aumentada"
    else:
        return "Não é um tipo padrão de tríade"
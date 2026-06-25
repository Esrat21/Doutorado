CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def classificar_triade(notas: list[str]) -> str:
    if len(notas) != 3:
        return "Erro: A tríade deve conter exatamente três notas."

    # Mapeia as notas para seus índices semitons (0-11)
    indices = []
    for nota in notas:
        try:
            idx = CROMATICA.index(nota)
            indices.append(idx)
        except ValueError:
            return "Erro: Nota inválida fornecida."

    # Ordena os índices para garantir que a terceira e a quinta sejam calculadas corretamente em relação à tônica (root)
    indices_ordenados = sorted(indices)

    # Calcula o intervalo da terça (T3) e da quinta (P5) em semitons, relativo ao primeiro elemento (tônica).
    # O módulo 12 garante que os intervalos sejam calculados dentro de uma oitava.
    intervalo_terca = (indices_ordenados[1] - indices_ordenados[0]) % 12
    intervalo_quinta = (indices_ordenados[2] - indices_ordenados[0]) % 12

    # Classificação baseada nos intervalos semitonais padrão:
    # Major: (4, 7)
    # Minor: (3, 7)
    # Diminuta: (1, 6)
    # Aumentada: (4, 8)

    if intervalo_terca == 4 and intervalo_quinta == 7:
        return "maior"
    elif intervalo_terca == 3 and intervalo_quinta == 7:
        return "menor"
    elif intervalo_terca == 1 and intervalo_quinta == 6:
        return "diminuta"
    elif intervalo_terca == 4 and intervalo_quinta == 8:
        return "aumentada"
    else:
        # Caso não se encaixe em nenhum padrão clássico (ex: tríade alterada)
        return "indefinida"
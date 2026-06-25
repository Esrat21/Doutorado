CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def classificar_triade(notas: list[str]) -> str:
    if len(notas) != 3:
        return "Erro: Tríade deve ter exatamente três notas."

    indices = []
    for nota in notas:
        try:
            indice = CROMATICA.index(nota)
            indices.append(indice)
        except ValueError:
            return "Erro: Nota inválida na lista fornecida."

    # Ordena os índices para garantir que Root, Third e Fifth sejam calculados corretamente
    indices.sort()

    # Calcula os intervalos em semitons (Root -> Third, Root -> Fifth)
    intervalo_terca = indices[1] - indices[0]
    intervalo_quinta = indices[2] - indices[0]

    # Classificação baseada nos padrões de intervalo:
    # Major: (4, 7)
    if intervalo_terca == 4 and intervalo_quinta == 7:
        return "maior"
    # Minor: (3, 7)
    elif intervalo_terca == 3 and intervalo_quinta == 7:
        return "menor"
    # Diminished: (3, 6)
    elif intervalo_terca == 3 and intervalo_quinta == 6:
        return "diminuta"
    # Augmented: (4, 8)
    elif intervalo_terca == 4 and intervalo_quinta == 8:
        return "aumentada"
    else:
        return "Não é uma tríade padrão identificável."
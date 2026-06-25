CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def classificar_triade(notas: list[str]) -> str:
    if len(notas) != 3:
        return "Erro: Tríade deve ter exatamente três notas."

    try:
        idx_r = CROMATICA.index(notas[0])
        idx_n2 = CROMATICA.index(notas[1])
        idx_n3 = CROMATICA.index(notas[2])
    except ValueError:
        return "Erro: Uma ou mais notas não são reconhecidas."

    # Calcula os intervalos em semitons a partir da tônica (Root)
    intervalo_2 = (idx_n2 - idx_r) % 12
    intervalo_3 = (idx_n3 - idx_r) % 12

    # Identifica o terceiro e o quinto grau, assumindo que são os dois menores intervalos não nulos.
    # Ordenamos para garantir que I3 seja sempre menor ou igual a I5.
    intervalos = sorted([intervalo_2, intervalo_3])

    if intervalos[0] == 0 or intervalos[1] == 0:
        return "Erro: Notas repetidas ou erro de cálculo."

    i3 = intervalos[0]
    i5 = intervalos[1]

    # Classificação baseada nos intervalos (semitons)
    if i3 == 4 and i5 == 7:
        return "maior"
    elif i3 == 3 and i5 == 7:
        return "menor"
    elif i3 == 3 and i5 == 6:
        return "diminuta"
    elif i3 == 4 and i5 == 8:
        return "aumentada"
    else:
        # Caso não corresponda a nenhum padrão clássico (ex: intervalo de quinta perfeita, ou notas muito distantes)
        return "indefinida"
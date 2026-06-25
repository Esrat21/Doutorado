CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def _intervalo(n1, n2):
    return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


def detectar_inversao(notas: list[str]) -> str:
    # Determina qual nota é a mais grave (o baixo)
    pitch_values = [CROMATICA.index(nota) for nota in notas]
    min_pitch = min(pitch_values)

    # Encontra o nome da nota que corresponde ao pitch mínimo
    bass_note = notas[pitch_values.index(min_pitch)]

    # Assumindo que a primeira nota do input (notas[0]) é o fundamental/raiz para determinar os intervalos de referência
    fundamental = notas[0]
    terca = notas[1]
    quinta = notas[2]

    # Verifica qual das três notas constituintes do acorde está no baixo.
    # Se a nota mais grave for igual ao fundamental (notas[0]), é estado fundamental.
    if bass_note == fundamental:
        return 'estado fundamental'
    # Se a nota mais grave for igual à terça (notas[1]), é primeira inversão.
    elif bass_note == terca:
        return 'primeira inversao'
    # Se a nota mais grave for igual à quinta (notas[2]), é segunda inversão.
    elif bass_note == quinta:
        return 'segunda inversao'
    else:
        # Caso de segurança, embora teoricamente não deva ocorrer se o input tiver 3 notas distintas
        return "inversao indeterminada"
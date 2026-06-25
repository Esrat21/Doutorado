def classificar_tetrade(notas: list[str]) -> str:
    pitch_map = {
        "C": 0, "C#": 1, "Db": 1, "D": 2, "D#": 3, "Eb": 3, "E": 4, "F": 5,
        "F#": 6, "Gb": 6, "G": 7, "G#": 8, "Ab": 8, "A": 9, "A#": 10, "Bb": 10, "B": 11
    }

    if len(notas) != 4:
        return "Erro: A entrada deve conter exatamente quatro notas."

    try:
        pitches = [pitch_map[nota] for nota in notas]
    except KeyError:
        return "Erro: Nota inválida fornecida."

    root_pitch = pitches[0]
    intervals = []
    for i in range(1, 4):
        interval = abs(pitches[i] - root_pitch) % 12
        # Garantir que o intervalo seja positivo e represente a distância cromática
        if interval == 0:
            continue # Não deve acontecer se as notas forem distintas

        intervals.append(interval)

    # Ordenar os intervalos para comparação padrão (m3, P5, M7 etc.)
    intervals.sort()

    # Definindo padrões de intervalo em semitons (Root, Interval 1, Interval 2, Interval 3)
    # Os intervalos são calculados a partir da tônica e devem ser ordenados: m3, P5, M7/m7
    patterns = {
        (3, 7, 11): "maior7",
        (4, 7, 10): "dominante7",
        (3, 7, 10): "menor7",
        (3, 6, 10): "meio_diminuto"
    }

    # Comparar os intervalos encontrados com os padrões definidos
    for pattern, chord_type in patterns.items():
        if intervals == list(pattern):
            return chord_type

    return "Tipo de tétrade não identificado ou inválido."
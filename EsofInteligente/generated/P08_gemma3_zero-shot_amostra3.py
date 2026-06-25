def classificar_tetrade(notas: list[str]) -> str:
    pitch_map = {
        "C": 0, "B#": 0,
        "C#": 1, "Db": 1,
        "D": 2,
        "D#": 3, "Eb": 3,
        "E": 4,
        "F": 5,
        "F#": 6, "Gb": 6,
        "G": 7,
        "G#": 8, "Ab": 8,
        "A": 9,
        "A#": 10, "Bb": 10,
        "B": 11,
    }

    if len(notas) != 4:
        return "Erro: A entrada deve conter exatamente quatro notas."

    root_name = notas[0]
    if root_name not in pitch_map:
        return "Erro: Nota de raiz inválida."

    root_semitone = pitch_map[root_name]
    other_notes = notas[1:]

    intervals = []
    for note_name in other_notes:
        if note_name not in pitch_map:
            return "Erro: Nota inválida na lista."
        note_semitone = pitch_map[note_name]
        interval = (note_semitone - root_semitone) % 12
        intervals.append(interval)

    # Ordena os intervalos para garantir uma comparação consistente, independentemente da ordem das notas de entrada
    intervals.sort()

    # Os padrões são definidos em semitons a partir da tônica (0).
    # Estrutura: [Intervalo 1, Intervalo 2, Intervalo 3]
    
    # M7: Major Third (4), Perfect Fifth (7), Major Seventh (11)
    if intervals == [4, 7, 11]:
        return "maior7"

    # D7: Major Third (4), Perfect Fifth (7), Minor Seventh (10)
    elif intervals == [4, 7, 10]:
        return "dominante7"

    # m7: Minor Third (3), Perfect Fifth (7), Minor Seventh (10)
    elif intervals == [3, 7, 10]:
        return "menor7"

    # meio_diminuto (m7b5): Minor Third (3), Diminished Fifth (6), Minor Seventh (10)
    elif intervals == [3, 6, 10]:
        return "meio_diminuto"

    else:
        return "Tipo de tétrade não reconhecido"
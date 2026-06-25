def classificar_tetrade(notas: list[str]) -> str:
    pitch_map = {
        "C": 0, "B#": 0,
        "C#": 1, "Db": 1,
        "D": 2,
        "D#": 3, "Eb": 3,
        "E": 4,
        "F": 5, "E#": 5,
        "F#": 6, "Gb": 6,
        "G": 7,
        "G#": 8, "Ab": 8,
        "A": 9,
        "A#": 10, "Bb": 10,
        "B": 11,
    }

    def get_pitch(note: str) -> int:
        if note not in pitch_map:
            raise ValueError(f"Nota desconhecida: {note}")
        return pitch_map[note]

    try:
        pitches = [get_pitch(nota) for nota in notas]
    except ValueError as e:
        # Em um cenário real, seria melhor tratar o erro de forma mais elegante.
        # Para este exercício, assumimos que a entrada é válida.
        return "Erro na entrada"

    root_pitch = pitches[0]
    intervals = []

    for i in range(1, 4):
        interval = (pitches[i] - root_pitch) % 12
        intervals.append(interval)

    # Ordena e transforma em um conjunto para garantir unicidade dos intervalos
    intervals = sorted(list(set(intervals)))

    # Os intervalos esperados são sempre {0, I1, I2, I3} onde o 0 é implícito (a própria tônica).
    # Como calculamos apenas os três intervalos acima do tom, precisamos adicionar o 0 para comparação.
    full_intervals = sorted(list(set([0] + intervals)))

    if len(full_intervals) != 4:
        # Se houver notas repetidas ou dependências estranhas que resultem em menos de 4 intervalos únicos
        return "Não é uma tétrade válida"

    # Comparação dos padrões (intervalos semitons do tom até o mais alto)
    # Maj7: {0, 4, 7, 11}
    if full_intervals == [0, 4, 7, 11]:
        return "maior7"

    # Dom7: {0, 4, 7, 10}
    elif full_intervals == [0, 4, 7, 10]:
        return "dominante7"

    # m7: {0, 3, 7, 10}
    elif full_intervals == [0, 3, 7, 10]:
        return "menor7"

    # meio_diminuto (m7b5): {0, 3, 6, 10}
    elif full_intervals == [0, 3, 6, 10]:
        return "meio_diminuto"

    else:
        return "Tipo de tétrade não reconhecido"<unused56>
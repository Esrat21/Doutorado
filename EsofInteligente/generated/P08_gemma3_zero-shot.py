def classificar_tetrade(notas: list[str]) -> str:
    note_map = {
        "C": 0, "B": 11, "C#": 1, "Db": 1, "D": 2, "Eb": 3, "D#": 3, "E": 4,
        "F": 5, "Gb": 5, "F#": 6, "G": 7, "Ab": 8, "G#": 8, "A": 9, "Bb": 10, "A#": 10, "B": 11
    }

    def get_pitch(note: str) -> int:
        if note.upper() in note_map:
            return note_map[note.upper()]
        raise ValueError(f"Nota desconhecida: {note}")

    pitches = [get_pitch(nota) for nota in notas]
    
    # Determina o pitch mais baixo (a tônica/raiz)
    root_pitch = min(pitches)
    
    # Calcula os intervalos em semitons em relação à raiz e normaliza para 0-11
    intervals = []
    for p in pitches:
        interval = (p - root_pitch) % 12
        if interval > 0: # Ignora a própria tônica se estiver na lista, mas garante que o intervalo seja calculado.
            intervals.append(interval)

    # Garante que temos exatamente 4 intervalos e os ordena para comparação
    sorted_intervals = sorted(list(set(intervals)))
    
    if len(sorted_intervals) < 3:
        return "Incompleta" # Caso haja notas repetidas ou erro de input

    # Os intervalos esperados (em semitons, ordenados):
    # [0] é implicitamente o primeiro elemento. Compararemos os 3 restantes.
    
    # Estruturas de intervalo (semitons) para as 4 notas:
    # R, M3, P5, M7 -> Maj7: [0, 4, 7, 11]
    # R, m3, P5, m7 -> m7:   [0, 3, 7, 10]
    # R, M3, P5, m7 -> Dom7: [0, 4, 7, 10]
    # R, m3, d5, m7 -> half_dim: [0, 3, 6, 10]

    # Os intervalos calculados já incluem o intervalo de 0 (a própria tônica) se for o menor pitch.
    # Para simplificar a comparação, vamos garantir que os 4 intervalos sejam comparados diretamente.
    
    if len(sorted_intervals) != 4:
        # Se houver notas repetidas ou um erro na lógica de cálculo, retornamos uma mensagem genérica.
        pass

    # Reorganizando para incluir o intervalo zero (a tônica) e ordenar os 4 intervalos.
    all_intervals = sorted([p - root_pitch for p in pitches])
    unique_sorted_intervals = []
    for i, interval in enumerate(all_intervals):
        if not unique_sorted_intervals or interval != unique_sorted_intervals[-1]:
            unique_sorted_intervals.append(interval)

    # Se o número de intervalos únicos for 4 (o que é esperado para uma tétrade completa e não repetitiva)
    if len(unique_sorted_intervals) == 4:
        intervals_tuple = tuple(unique_sorted_intervals)
        
        major7_intervals = (0, 4, 7, 11)
        minor7_intervals = (0, 3, 7, 10)
        dominant7_intervals = (0, 4, 7, 10)
        half_diminished_intervals = (0, 3, 6, 10)

        if intervals_tuple == major7_intervals:
            return "major7"
        elif intervals_tuple == minor7_intervals:
            return "minor7"
        elif intervals_tuple == dominant7_intervals:
            return "dominant7"
        elif intervals_tuple == half_diminished_intervals:
            return "meio_diminuto"

    # Se a tétrade não corresponder exatamente aos padrões, retorna um valor padrão.
    return ""<unused56>
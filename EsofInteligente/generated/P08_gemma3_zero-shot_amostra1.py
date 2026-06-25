def classificar_tetrade(notas: list[str]) -> str:
    NOTE_MAP = {
        'C': 0, 'B#': 0,
        'Db': 0,
        'D': 2, 'C#': 1, 'D#': 3,
        'Eb': 3,
        'E': 4,
        'Fb': 4,
        'F': 5, 'E#': 5,
        'Gb': 5,
        'G': 7, 'F#': 6, 'G#': 8,
        'Ab': 8,
        'A': 9, 'G#': 8, # Handling enharmonics for robustness, though standard input should be consistent
        'Bb': 10, 'A#': 11,
        'B': 11,
    }

    def get_semitone(note: str) -> int:
        """Converte nome de nota para semitom (0-11)."""
        # Normaliza o input para garantir que seja encontrado no mapa
        normalized_note = note.strip().replace(' ', '')
        if normalized_note not in NOTE_MAP:
            raise ValueError(f"Nota desconhecida: {note}")
        return NOTE_MAP[normalized_note]

    try:
        semitones = [get_semitone(n) for n in notas]
    except ValueError as e:
        # Em um ambiente real, seria melhor retornar um erro específico. 
        # Aqui, assumimos que o input é válido ou levantamos a exceção.
        raise e

    root_semitones = semitones[0]
    
    # Calcula os intervalos em semitons relativos à tônica (Root)
    intervals = []
    for s in semitones:
        interval = (s - root_semitones) % 12
        intervals.append(interval)

    # Ordena e remove duplicatas para obter o conjunto de intervalos únicos
    sorted_intervals = sorted(list(set(intervals)))

    # Define os padrões de intervalo esperados (em semitons, ordenados)
    # R=0 é sempre incluído pelo cálculo acima.
    patterns = {
        'maior7': [0, 4, 7, 11],  # Root, M3, P5, M7
        'dominante7': [0, 4, 7, 10], # Root, M3, P5, m7
        'menor7': [0, 3, 7, 10],   # Root, m3, P5, m7
        'meio_diminuto': [0, 3, 6, 10] # Root, m3, d5, m7
    }

    # Compara o conjunto de intervalos encontrado com os padrões definidos
    for chord_type, expected_intervals in patterns.items():
        if sorted_intervals == expected_intervals:
            return chord_type

    # Se não corresponder a nenhum padrão conhecido
    return "Tipo desconhecido"<unused56>
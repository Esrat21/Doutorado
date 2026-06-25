def classificar_tetrade(notas: list[str]) -> str:
    PITCH_MAP = {
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
        "B": 11
    }

    def get_pitch(note: str) -> int:
        if note.upper() not in PITCH_MAP:
            raise ValueError(f"Nota desconhecida: {note}")
        return PITCH_MAP[note.upper()]

    try:
        pitches = [get_pitch(nota) for nota in notas]
    except ValueError as e:
        # Em um ambiente real, você retornaria um erro específico. Aqui, assumimos que a entrada é válida.
        return "Erro de Entrada" 

    root_pitch = pitches[0]
    intervals = []

    for pitch in pitches:
        interval = (pitch - root_pitch) % 12
        intervals.append(interval)

    # Ordena os intervalos para comparação padrão
    sorted_intervals = sorted(list(set(intervals)))

    # Se houver menos de 4 notas únicas, o cálculo pode ser impreciso, mas seguiremos a lógica dos 4 elementos fornecidos.
    if len(sorted_intervals) < 4:
        # Caso as notas sejam repetidas ou haja pouca variação, usamos os 4 intervalos calculados (mesmo que não únicos).
        pass

    # Os padrões de intervalo semitom do Root até o 7º grau (ordenado):
    # [0, M3, P5, M7] -> Major 7th: 0, 4, 7, 11
    # [0, m3, P5, m7] -> Minor 7th: 0, 3, 7, 10
    # [0, M3, P5, m7] -> Dominant 7th: 0, 4, 7, 10
    # [0, m3, d5, m7] -> Half-Diminished: 0, 3, 6, 10

    patterns = {
        tuple(sorted([0, 4, 7, 11])): "maior7",
        tuple(sorted([0, 3, 7, 10])): "menor7",
        tuple(sorted([0, 4, 7, 10])): "dominante7",
        tuple(sorted([0, 3, 6, 10])): "meio_diminuto"
    }

    # Usamos os intervalos calculados (que já incluem o intervalo 0) e garantimos que temos 4 elementos para comparação.
    if len(intervals) < 4:
        return "Tétrade incompleta ou não padrão" # Fallback case

    # Para garantir a comparação correta, usamos os quatro valores de intervalo calculados (mesmo que haja repetição).
    # No entanto, como o problema é sobre *classificação* e não apenas lista de notas, 
    # comparamos com o conjunto único ordenado para robustez.
    interval_tuple = tuple(sorted([i for i in intervals]))

    if interval_tuple in patterns:
        return patterns[interval_tuple]
    else:
        return "Não classificado"<unused56>
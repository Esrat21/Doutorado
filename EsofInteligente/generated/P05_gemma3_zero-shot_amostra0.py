def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    # Mapeamento de notas cromáticas usando apenas sustenidos para garantir a conformidade com o requisito.
    # A ordem é baseada em semitons (0=tônica).
    PITCHES = {
        "C": ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"],
        "G": ["G", "G#", "A", "A#", "B", "C", "C#", "D", "D#", "E", "F", "F#"],
        "D": ["D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B", "C#", "D#"],
        "A": ["A", "A#", "B", "C", "C#", "D", "D#", "E", "F", "F#", "G", "G#"]
    }

    if tonalidade.upper() not in PITCHES:
        raise ValueError("Tonalidade não suportada ou fora do padrão de sustenidos (use G, D, A).")

    pitch_map = PITCHES[tonalidade.upper()]
    root_index = pitch_map.index(tonalidade) if tonalidade in pitch_map else 0

    # Intervalos em semitons para os graus da escala maior:
    # I (0), ii (2), iii (4), IV (5), V (7), vi (9), vii° (11)
    intervals = [0, 2, 4, 5, 7, 9, 11]

    def get_note(semitones):
        """Calcula a nota no índice semitom e retorna o nome da nota."""
        index = (root_index + semitones) % 12
        return pitch_map[index]

    # Calcula as raízes dos sete acordes
    roots = [get_note(i) for i in intervals]

    # Define a qualidade do acorde para cada grau: I, ii, iii, IV, V, vi, vii°
    qualidades = ["maj", "min", "min", "maj", "maj", "min", "dim"]

    campo_harmonico = []
    for i in range(7):
        root = roots[i]
        quality = qualidades[i]
        # Exceção: O acorde vii° é frequentemente escrito com o nome da tônica (ex: G#dim)
        if quality == "dim":
            campo_harmonico.append(f"{root}{quality}")
        else:
            campo_harmonico.append(f"{root}{quality}")

    return campo_harmonico
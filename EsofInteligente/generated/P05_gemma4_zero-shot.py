def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    semitones = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5, "F#": 6,
        "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }

    def get_note(semitone_offset: int) -> str:
        """Calcula o nome da nota a partir de um offset em semitons."""
        start_index = semitones.get(tonalidade.upper(), "C").lower()
        current_index = semitones["C"].lower() if tonalidade.upper() == "C" else start_index
        
        # Ajusta o índice inicial para garantir que a transposição funcione corretamente
        if tonalidade.upper() != "C":
            start_semitone = semitones[tonalidade.upper()]
        else:
            start_semitone = 0

        target_semitone = (start_semitone + semitone_offset) % 12
        
        # Mapeamento de volta para o nome da nota, garantindo apenas sustenidos
        note_map = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
        return note_map[target_semitone]

    def get_chord_name(root: str, quality: str) -> str:
        """Formata o nome do acorde (ex: 'Cmaj', 'Amin')."""
        if quality == "major":
            return f"{root}maj"
        elif quality == "minor":
            return f"{root}min"
        elif quality == "diminished":
            # Para vii°, usamos o símbolo de diminuto (º) ou simplesmente 'dim'
            return f"{root}dim"

    # Intervalos em semitons a partir da tônica:
    # I: Major (0, 4, 7)
    # ii: Minor (0, 3, 7)
    # iii: Minor (0, 3, 7)
    # IV: Major (0, 4, 7)
    # V: Major (0, 4, 7)
    # vi: Minor (0, 3, 7)
    # vii°: Diminished (0, 3, 6)

    # A função só precisa do nome da tônica e da qualidade.
    # Os intervalos internos não são necessários para a saída solicitada.

    field = [
        ("I", "major"),
        ("ii", "minor"),
        ("iii", "minor"),
        ("IV", "major"),
        ("V", "major"),
        ("vi", "minor"),
        ("vii°", "diminished")
    ]

    chord_list = []
    for _, quality in field:
        # A tônica do acorde é sempre a tonalidade fornecida.
        chord_name = get_chord_name(tonalidade, quality)
        chord_list.append(chord_name)

    return chord_list
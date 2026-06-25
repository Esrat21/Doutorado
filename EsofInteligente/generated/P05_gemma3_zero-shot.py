def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    notes = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    semitones_map = {note: i for i, note in enumerate(notes)}

    def get_sharp_name(pitch_index):
        return notes[pitch_index % 12]

    try:
        root_index = semitones_map[tonalidade.upper()]
    except KeyError:
        raise ValueError("Tonalidade inválida. Use um nome de nota (ex: C, G, D).")

    # Define os intervalos em semitons para o campo harmônico maior
    # I: 0 (Root)
    # ii: 2 semitones acima da tônica (m3)
    # iii: 4 semitones acima da tônica (m3)
    # IV: 7 semitones acima da tônica (M3)
    # V: 9 semitones acima da tônica (M3)
    # vi: 12 semitones acima da tônica (m3) -> No ciclo, é o mesmo que ii.
    # vii°: 14 semitones acima da tônica (diminished third)

    # Intervalos de construção dos acordes em relação à tônica (em semitons):
    # [I, II, III, IV, V, VI, VII]
    intervals = [0, 2, 4, 5, 7, 9, 11] # Estes são os graus da escala: R, m3, P4, M3, P5, m6, d7

    # Ajustando para a estrutura de acordes (I=M, ii=m, iii=m, IV=M, V=M, vi=m, vii°=dim)
    # Os índices abaixo representam os semitons do terceiro grau em relação à tônica.
    chord_intervals = [0, 2, 4, 5, 7, 9, 11] # Estes são os graus da escala (semitones from root)

    # Ajustando a lista de intervalos para garantir que o vii° seja calculado corretamente:
    # I: R + M3 (4 semitons) -> Não, I é apenas a tônica.
    # Vamos usar as notas da escala e calcular os acordes sobre elas.

    scale_degrees = [0, 2, 4, 5, 7, 9, 11] # Semitones from root: R, m3, P4, M3, P5, m6, d7
    
    # Ajuste para a estrutura diatônica correta (semitons):
    # I: 0
    # ii: 2 semitons acima da tônica (m3)
    # iii: 4 semitons acima da tônica (m3)
    # IV: 5 semitons acima da tônica (M3)
    # V: 7 semitons acima da tônica (M3)
    # vi: 9 semitons acima da tônica (m3)
    # vii°: 11 semitones acima da tônica (diminished third)

    chord_intervals = [0, 2, 4, 5, 7, 9, 11] # Estes são os intervalos corretos em relação à tônica.

    acordes = []
    for i in range(7):
        # Calcula o índice da nota raiz do acorde (em semitons)
        root_index = (root_index + chord_intervals[i]) % 12
        root_name = get_sharp_name(root_index)

        if i == 0: # I - Maior
            acorde_str = f"{root_name}maj"
        elif i == 1: # ii - Menor
            acorde_str = f"{root_name}min"
        elif i == 2: # iii - Menor
            acorde_str = f"{root_name}min"
        elif i == 3: # IV - Maior
            acorde_str = f"{root_name}maj"
        elif i == 4: # V - Maior
            acorde_str = f"{root_name}maj"
        elif i == 5: # vi - Menor
            acorde_str = f"{root_name}min"
        else: # vii° - Diminuto
            # Nota: O acorde diminuto é construído sobre o grau VII.
            acorde_str = f"{root_name}dim"

        acordes.append(acorde_str)

    return acordes
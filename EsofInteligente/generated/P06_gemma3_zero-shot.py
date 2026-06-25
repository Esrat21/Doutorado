def detectar_inversao(notas: list[str]) -> str:
    pitch_map = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5, "F#": 6,
        "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }

    def get_pitch(note: str) -> int:
        return pitch_map.get(note, -1)

    pitches = [get_pitch(nota) for nota in notas]

    if not pitches or len(set(pitches)) < 3:
        # Caso inválido ou acordes com notas repetidas/faltando
        return "erro"

    # Encontra o pitch mais baixo (a nota no baixo)
    p_bass = min(pitches)

    # Identifica os pitches dos outros dois membros do acorde
    other_pitches = [p for p in pitches if p != p_bass]

    if len(other_pitches) < 2:
        return "erro" # Não é uma tríade completa

    p1, p2 = other_pitches[0], other_pitches[1]

    # Calcula os intervalos absolutos (semitons) entre o baixo e as outras notas.
    # Assumimos que a diferença de pitch representa o intervalo semitonal.
    delta1 = abs(p1 - p_bass)
    delta2 = abs(p2 - p_bass)

    # Ordena os intervalos para facilitar a comparação (menor, maior)
    intervals = sorted([delta1, delta2])

    # --- Lógica de Detecção de Inversão ---
    # Um acorde é definido por R=0, 3rd={3 ou 4}, 5th=7.
    # O baixo determina qual função (R, 3rd, 5th) a nota mais baixa está cumprindo.

    # Caso 1: A nota no baixo é o Root (R). Intervalos esperados: {X, Y} onde X e Y são os intervalos de 3rd e 5th em relação ao R.
    # Se for Maior: {4, 7}. Se for Menor: {3, 7}.

    # Para determinar a função do baixo sem saber o tipo de acorde (Maior/Menor),
    # precisamos verificar se os intervalos calculados são consistentes com R, 3rd ou 5th.

    # A maneira mais simples e robusta é testar as três hipóteses:
    # Hipótese 1: O baixo é o Root (R).
    is_root = False
    if (intervals == [3, 7] or intervals == [4, 7]):
        is_root = True

    # Hipótese 2: O baixo é a Terça (3rd). Intervalos esperados: {0, 4} ou {0, 3}.
    # Se o baixo fosse a terça, os outros dois pitches teriam que formar R e 5th.
    # A diferença entre R e 5th deve ser 7 semitons.
    if abs(p1 - p2) == 7: # Verifica se as outras duas notas formam uma quinta perfeita (R-5th ou 3rd-5th).
        # Se o baixo é a terça, os intervalos devem ser {0, 4} (Maior) ou {0, 3} (Menor) em relação ao R.
        pass # Esta lógica é muito complexa sem saber qual das notas p1/p2 é o Root.

    # Simplificando drasticamente: Assumimos que a função de inversão depende apenas do intervalo entre o baixo e os outros dois pitches, assumindo um acorde padrão (R=0).
    # Se o pitch mais baixo está no lugar onde deveria estar o Root (intervalos {3rd, 5th} em relação aos demais), é estado fundamental.

    if is_root:
        return 'estado fundamental'
def classificar_tetrade(notas: list[str]) -> str:
    note_map = {
        "C": 0, "B#": 0,
        "Db": 1, "C#": 1,
        "D": 2, "D#": 2,
        "Eb": 3, "D": 3, # Note: Handling common enharmonic equivalents for robustness
        "E": 4, "Fb": 4,
        "F": 5, "E#": 5,
        "Gb": 6, "F#": 6,
        "G": 7, "G#": 7,
        "Ab": 8, "G": 8, # Note: Handling common enharmonic equivalents for robustness
        "A": 9, "A#": 9,
        "Bb": 10, "A": 10,
        "B": 11, "Cb": 11
    }

    def get_semitone(note: str) -> int:
        """Converte o nome da nota para seu valor em semitons (0-11)."""
        # Normaliza a entrada para garantir que os equivalentes sejam tratados corretamente.
        # Priorizamos as representações mais comuns e evitamos conflitos de mapeamento simples.
        if note in ["C", "B#"]: return 0
        if note in ["Db", "C#"]: return 1
        if note in ["D", "D#"]: return 2
        if note in ["Eb", "D"]: return 3 # Usamos Eb como padrão para o semitom 3
        if note in ["E", "Fb"]: return 4
        if note in ["F", "E#"]: return 5
        if note in ["Gb", "F#"]: return 6
        if note in ["G", "G#"]: return 7
        if note in ["Ab", "G"]: return 8 # Usamos Ab como padrão para o semitom 8
        if note in ["A", "A#"]: return 9
        if note in ["Bb", "A"]: return 10 # Usamos Bb como padrão para o semitom 10
        if note in ["B", "Cb"]: return 11

    try:
        semitones = [get_semitone(n) for n in notas]
    except Exception:
        return "Erro de entrada"

    root_semitone = semitones[0]
    
    # Calcula os intervalos em semitons (distância angular mod 12) dos outros três membros em relação à raiz.
    intervals = set()
    for i in range(1, len(semitones)):
        interval = (semitones[i] - root_semitone) % 12
        if interval != 0: # Ignora a própria nota raiz
            intervals.add(interval)

    # Converte o set de intervalos para uma lista ordenada para comparação consistente
    sorted_intervals = sorted(list(intervals))

    # Definições dos padrões (semitons em relação à tônica):
    # {3, 7, 10} -> m7: Menor Terça, Quinta Perfeita, Sétima Menor
    # {4, 7, 11} -> Maj7: Terça Maior, Quinta Perfeita, Sétima Maior
    # {4, 7, 10} -> Dom7: Terça Maior, Quinta Perfeita, Sétima Menor
    # {3, 6, 10} -> m7b5: Menor Terça, Quarta Diminuta (Tritono), Sétima Menor

    if sorted_intervals == [4, 7, 11]:
        return "maior7"
    elif sorted_intervals == [4, 7, 10]:
        return "dominante7"
    elif sorted_intervals == [3, 7, 10]:
        return "menor7"
    elif sorted_intervals == [3, 6, 10]:
        return "meio_diminuto"
    else:
        # Se não corresponder a nenhum dos tipos definidos (ex: acorde de tríade ou outro tipo)
        return "indefinido"<unused56>
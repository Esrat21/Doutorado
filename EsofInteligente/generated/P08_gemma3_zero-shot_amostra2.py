import collections
from typing import List

def classificar_tetrade(notas: list[str]) -> str:
    pitch_map = {
        "C": 0, "B": 1, "A": 2, "G": 3, "F": 4, "E": 5, "D": 6,
        "C#": 7, "Db": 8, "D#": 9, "Eb": 10, "Fb": 11,
        "Cb": 0 # Placeholder for wrap-around logic if needed, but standard mapping is fine.
    }

    def get_semitone(note: str) -> int:
        if note.upper() in pitch_map:
            return pitch_map[note.upper()]
        # Handle common enharmonic equivalents (e.g., Gb vs F#) if necessary, 
        # but for simplicity and standard input handling, we rely on the defined map.
        raise ValueError(f"Nota desconhecida: {note}")

    try:
        root_semitones = get_semitone(notas[0])
        
        intervals = [0] # O intervalo da nota raiz para ela mesma é 0 semitons
        for i in range(1, len(notas)):
            note_semitones = get_semitone(notas[i])
            # Calcula o intervalo em semitons e normaliza (modulo 12)
            interval = (note_semitones - root_semitones) % 12
            intervals.append(interval)

        sorted_intervals = sorted(list(set(intervals))) # Remove duplicatas e ordena

    except ValueError:
        return "Erro de entrada"

    # Definindo os padrões de intervalos (em semitons, ordenados)
    patterns = {
        "maior7": [0, 4, 7, 11],  # R + M3 + P5 + M7
        "dominante7": [0, 4, 7, 10], # R + M3 + P5 + m7
        "menor7": [0, 3, 7, 10],   # R + m3 + P5 + m7
        "meio_diminuto": [0, 3, 6, 10] # R + m3 + d5 + m7
    }

    for chord_type, expected_intervals in patterns.items():
        if sorted_intervals == expected_intervals:
            return chord_type
    
    # Se não corresponder a nenhum padrão conhecido
    return "Tipo de tétrade desconhecido"
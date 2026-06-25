def classificar_triade(notas: list[str]) -> str:
       notas_map = {
           "C": 0, "C#": 1, "DB": 1, "D": 2, "D#": 3, "EB": 3,
           "E": 4, "F": 5, "F#": 6, "GB": 6, "G": 7, "G#": 8,
           "AB": 8, "A": 9, "A#": 10, "BB": 10, "B": 11
       }
       
       def get_semitone(note):
           n = note.upper().replace("♭", "B")
           return notas_map[n]
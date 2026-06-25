def classificar_tetrade(notas: list[str]) -> str:
       notes_map = {
           'C': 0, 'DB': 1, 'C#': 1, 'D': 2, 'EB': 3, 'D#': 3, 'E': 4,
           'F': 5, 'GB': 6, 'F#': 6, 'G': 7, 'AB': 8, 'G#': 8, 'A': 9,
           'BB': 10, 'A#': 10, 'B': 11
       }
       
       def get_semitone(note):
           return notes_map.get(note.strip().upper())
           
       root = get_semitone(notas[0])
       intervals = [(get_semitone(n) - root) % 12 for n in notas[1:]]
       
       if intervals == [4, 7, 11]:
           return 'maior7'
       elif intervals == [4, 7, 10]:
           return 'dominante7'
       elif intervals == [3, 7, 10]:
           return 'menor7'
       elif intervals == [3, 6, 10]:
           return 'meio_diminuto'
       return ''
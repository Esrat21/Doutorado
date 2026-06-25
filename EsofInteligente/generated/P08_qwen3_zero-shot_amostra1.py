def classificar_tetrade(notas: list[str]) -> str:
         note_map = {
             'C': 0, 'C#': 1, 'Db': 1, 'D': 2, 'D#': 3, 'Eb': 3,
             'E': 4, 'Fb': 4, 'E#': 5, 'F': 5, 'F#': 6, 'Gb': 6,
             'G': 7, 'G#': 8, 'Ab': 8, 'A': 9, 'A#': 10, 'Bb': 10,
             'B': 11, 'Cb': 11, 'B#': 12
         }
         def get_semi(n):
             n = n.replace('♯', '#').replace('♭', 'b')
             return note_map.get(n, -1) % 12

         root = get_semi(notas[0])
         intervals = [(get_semi(n) - root) % 12 for n in notas]
         i1, i2, i3 = intervals[1], intervals[2], intervals[3]

         if i1 == 4 and i2 == 7 and i3 == 11:
             return 'maior7'
         if i1 == 4 and i2 == 7 and i3 == 10:
             return 'dominante7'
         if i1 == 3 and i2 == 7 and i3 == 10:
             return 'menor7'
         if i1 == 3 and i2 == 6 and i3 == 10:
             return 'meio_diminuto'
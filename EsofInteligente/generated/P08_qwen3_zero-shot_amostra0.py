def classificar_tetrade(notas: list[str]) -> str:
       notas_map = {
           'C': 0, 'C#': 1, 'Db': 1, 'D': 2, 'D#': 3, 'Eb': 3,
           'E': 4, 'F': 5, 'F#': 6, 'Gb': 6, 'G': 7, 'G#': 8,
           'Ab': 8, 'A': 9, 'A#': 10, 'Bb': 10, 'B': 11
       }
       notas = [n.upper() for n in notas]
       r = notas_map[notas[0]]
       i1 = (notas_map[notas[1]] - r) % 12
       i2 = (notas_map[notas[2]] - r) % 12
       i3 = (notas_map[notas[3]] - r) % 12

       if i1 == 4 and i2 == 7 and i3 == 11:
           return 'maior7'
       if i1 == 4 and i2 == 7 and i3 == 10:
           return 'dominante7'
       if i1 == 3 and i2 == 7 and i3 == 10:
           return 'menor7'
       if i1 == 3 and i2 == 6 and i3 == 10:
           return 'meio_diminuto'
def classificar_tetrade(notas: list[str]) -> str:
       notas_para_semitons = {
           'C': 0, 'Db': 1, 'C#': 1, 'D': 2, 'Eb': 3, 'D#': 3,
           'E': 4, 'Fb': 4, 'F': 5, 'E#': 5, 'F#': 6, 'Gb': 6,
           'G': 7, 'Ab': 8, 'G#': 8, 'A': 9, 'Bb': 10, 'A#': 10,
           'B': 11, 'Cb': 11, 'B#': 0
       }
       def normalizar(n):
           return n[0].upper() + n[1:] if len(n) > 1 else n.upper()
       semitons = [(notas_para_semitons[normalizar(n)] - notas_para_semitons[normalizar(notas[0])]) % 12 for n in notas]
       if semitons == [0, 4, 7, 11]: return 'maior7'
       if semitons == [0, 4, 7, 10]: return 'dominante7'
       if semitons == [0, 3, 7, 10]: return 'menor7'
       if semitons == [0, 3, 6, 10]: return 'meio_diminuto'
       return ''
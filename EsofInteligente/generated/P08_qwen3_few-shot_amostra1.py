def classificar_tetrade(notas: list[str]) -> str:
       notas_base = {'C': 0, 'D': 2, 'E': 4, 'F': 5, 'G': 7, 'A': 9, 'B': 11}
       def para_semitono(n):
           base = n[0].upper()
           val = notas_base.get(base, 0)
           for c in n[1:]:
               if c == '#':
                   val += 1
               elif c == 'b':
                   val -= 1
           return val % 12

       semitons = [para_semitono(n) for n in notas]
       root = semitons[0]
       intervals = [(s - root) % 12 for s in semitons[1:]]

       if intervals == [4, 7, 11]:
           return "maior7"
       elif intervals == [4, 7, 10]:
           return "dominante7"
       elif intervals == [3, 7, 10]:
           return "menor7"
       elif intervals == [3, 6, 10]:
           return "meio_diminuto"
       return ""
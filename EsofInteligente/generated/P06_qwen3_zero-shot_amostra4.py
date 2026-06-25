def detectar_inversao(notas: list[str]) -> str:
       base_map = {'C': 0, 'D': 2, 'E': 4, 'F': 5, 'G': 7, 'A': 9, 'B': 11}

       def to_pc(note):
           base = note[0]
           suffix = note[1:] if len(note) > 1 else ''
           offset = 0
           if suffix == '#':
               offset = 1
           elif suffix == 'b':
               offset = -1
           return (base_map[base] + offset) % 12

       pcs = [to_pc(n) for n in notas]
       bass_pc = pcs[0]

       root_pc = None
       third_pc = None
       fifth_pc = None

       for r in pcs:
           intervals = sorted([(x - r) % 12 for x in pcs])
           if intervals == [0, 3, 7]:
               root_pc = r
               third_pc = (r + 3) % 12
               fifth_pc = (r + 7) % 12
               break
           elif intervals == [0, 4, 7]:
               root_pc = r
               third_pc = (r + 4) % 12
               fifth_pc = (r + 7) % 12
               break

       if bass_pc == root_pc:
           return 'estado fundamental'
       elif bass_pc == third_pc:
           return 'primeira inversao'
       elif bass_pc == fifth_pc:
           return 'segunda inversao'
       return 'acorde inválido'
def detectar_inversao(notas: list[str]) -> str:
       notas_map = {
           'C': 0, 'C#': 1, 'Db': 1, 'D': 2, 'D#': 3, 'Eb': 3,
           'E': 4, 'F': 5, 'F#': 6, 'Gb': 6, 'G': 7, 'G#': 8,
           'Ab': 8, 'A': 9, 'A#': 10, 'Bb': 10, 'B': 11
       }
       pcs = [notas_map[n] for n in notas]
       bass_pc = min(pcs)

       root_pc = None
       for r in range(12):
           if {r, (r+3)%12, (r+7)%12} == set(pcs) or {r, (r+4)%12, (r+7)%12} == set(pcs):
               root_pc = r
               break

       third_pc = (root_pc + 3) % 12 if {root_pc, (root_pc+3)%12, (root_pc+7)%12} == set(pcs) else (root_pc + 4) % 12
       fifth_pc = (root_pc + 7) % 12

       if bass_pc == root_pc:
           return 'estado fundamental'
       elif bass_pc == third_pc:
           return 'primeira inversao'
       elif bass_pc == fifth_pc:
           return 'segunda inversao'
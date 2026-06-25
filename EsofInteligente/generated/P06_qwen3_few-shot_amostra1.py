CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

   def detectar_inversao(notas: list[str]) -> str:
       for root in notas:
           others = [n for n in notas if n != root]
           if len(others) < 2: continue # handle duplicates if any, though unlikely
           iv1 = (CROMATICA.index(others[0]) - CROMATICA.index(root)) % 12
           iv2 = (CROMATICA.index(others[1]) - CROMATICA.index(root)) % 12
           if {iv1, iv2} == {3, 7} or {iv1, iv2} == {4, 7}:
               third_note = others[0] if iv1 in (3, 4) else others[1]
               fifth_note = others[1] if iv1 in (3, 4) else others[0]
               break

       bass = notas[0]
       if bass == root:
           return 'estado fundamental'
       if bass == third_note:
           return 'primeira inversao'
       return 'segunda inversao'
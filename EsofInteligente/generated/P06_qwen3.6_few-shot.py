CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

       def _intervalo(n1, n2):
           return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12

       def detectar_inversao(notas: list[str]) -> str:
           root = None
           for i in range(3):
               n = notas[i]
               others = [notas[j] for j in range(3) if j != i]
               intervals = {_intervalo(n, o) for o in others}
               if intervals == {3, 7} or intervals == {4, 7}:
                   root = n
                   break

           third = fifth = None
           for note in notas:
               if note != root:
                   iv = _intervalo(root, note)
                   if iv in (3, 4):
                       third = note
                   elif iv == 7:
                       fifth = note

           bass = notas[0]
           if bass == root:
               return "estado fundamental"
           if bass == third:
               return "primeira inversao"
           if bass == fifth:
               return "segunda inversao"
           return "desconhecida"
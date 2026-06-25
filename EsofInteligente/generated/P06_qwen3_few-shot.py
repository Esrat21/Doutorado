CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

     def _intervalo(n1, n2):
         return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12

     def detectar_inversao(notas: list[str]) -> str:
         root_idx = None
         for i in range(3):
             r = notas[i]
             others = [notas[j] for j in range(3) if j != i]
             ivs = sorted([_intervalo(r, o) for o in others])
             if ivs == [3, 7] or ivs == [4, 7]:
                 root_idx = i
                 break

         third_idx = None
         for j in range(3):
             if j != root_idx:
                 if _intervalo(notas[root_idx], notas[j]) in (3, 4):
                     third_idx = j
                     break

         if root_idx == 0:
             return "estado fundamental"
         if third_idx == 0:
             return "primeira inversao"
         return "segunda inversao"
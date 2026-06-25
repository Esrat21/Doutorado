CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


   def _intervalo(n1, n2):
       return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12


   def detectar_inversao(notas: list[str]) -> str:
       for i, nota in enumerate(notas):
           outros = [n for j, n in enumerate(notas) if j != i]
           ints = sorted([_intervalo(nota, outros[0]), _intervalo(nota, outros[1])])
           if ints == [3, 7] or ints == [4, 7]:
               if i == 0:
                   return 'estado fundamental'
               elif i == 1:
                   return 'primeira inversao'
               else:
                   return 'segunda inversao'
       return 'desconhecida'
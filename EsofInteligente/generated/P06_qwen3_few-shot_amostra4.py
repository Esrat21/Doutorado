def detectar_inversao(notas: list[str]) -> str:
       CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
       def _intervalo(n1, n2):
           return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12

       raiz = None
       for i in range(3):
           n1 = notas[i]
           d1 = _intervalo(n1, notas[(i+1)%3])
           d2 = _intervalo(n1, notas[(i+2)%3])
           if (d1 == 4 and d2 == 7) or (d1 == 7 and d2 == 4):
               raiz = n1; break
           if (d1 == 3 and d2 == 7) or (d1 == 7 and d2 == 3):
               raiz = n1; break

       terca = None
       quinta = None
       for i in range(3):
           d = _intervalo(raiz, notas[i])
           if d in (3, 4):
               terca = notas[i]
           elif d == 7:
               quinta = notas[i]

       baixo = notas[0]
       if baixo == raiz:
           return 'estado fundamental'
       if baixo == terca:
           return 'primeira inversao'
       return 'segunda inversao'
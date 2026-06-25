def detectar_inversao(notas: list[str]) -> str:
         for n in notas:
             intervals = [(CROMATICA.index(m) - CROMATICA.index(n)) % 12 for m in notas if m != n]
             if (3 in intervals or 4 in intervals) and 7 in intervals:
                 fundamental = n
                 break
         terca = next(m for m in notas if m != fundamental and ((CROMATICA.index(m) - CROMATICA.index(fundamental)) % 12) in (3, 4))
         quinta = next(m for m in notas if m != fundamental and ((CROMATICA.index(m) - CROMATICA.index(fundamental)) % 12) == 7)

         baixo = notas[0]
         if baixo == fundamental:
             return "estado fundamental"
         if baixo == terca:
             return "primeira inversao"
         return "segunda inversao"
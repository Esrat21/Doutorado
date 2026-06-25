def classificar_triade(notas: list[str]) -> str:
       notas_base = {'C': 0, 'D': 2, 'E': 4, 'F': 5, 'G': 7, 'A': 9, 'B': 11}
       def para_grau(n):
           base = n[0].upper()
           grau = notas_base.get(base, 0)
           for c in n[1:]:
               if c == '#': grau += 1
               elif c == 'b': grau -= 1
           return grau % 12

       graus = [para_grau(n) for n in notas]
       i1 = (graus[1] - graus[0]) % 12
       i2 = (graus[2] - graus[0]) % 12

       if i1 == 4 and i2 == 7: return "maior"
       if i1 == 3 and i2 == 7: return "menor"
       if i1 == 3 and i2 == 6: return "diminuta"
       if i1 == 4 and i2 == 8: return "aumentada"
       return "desconhecida" # or just pass, but better to have a fallback
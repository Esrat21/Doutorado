def classificar_triade(notas: list[str]) -> str:
       notas_map = {
           'c': 0, 'c#': 1, 'db': 1, 'd': 2, 'd#': 3, 'eb': 3,
           'e': 4, 'f': 5, 'f#': 6, 'gb': 6, 'g': 7, 'g#': 8,
           'ab': 8, 'a': 9, 'a#': 10, 'bb': 10, 'b': 11
       }
       p = [notas_map[n.lower()] for n in notas]
       i1 = (p[1] - p[0]) % 12
       i2 = (p[2] - p[0]) % 12
       if i1 == 4 and i2 == 7:
           return "maior"
       elif i1 == 3 and i2 == 7:
           return "menor"
       elif i1 == 3 and i2 == 6:
           return "diminuta"
       elif i1 == 4 and i2 == 8:
           return "aumentada"
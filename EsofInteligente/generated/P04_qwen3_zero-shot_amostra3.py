def classificar_triade(notas: list[str]) -> str:
       def get_semitone(n):
           n = n.strip().upper()
           base = {'C':0, 'D':2, 'E':4, 'F':5, 'G':7, 'A':9, 'B':11}
           val = base[n[0]]
           if len(n) > 1:
               if n[1] == '#': val += 1
               elif n[1] == 'b': val -= 1
           return val % 12

       root = get_semitone(notas[0])
       third = get_semitone(notas[1])
       fifth = get_semitone(notas[2])

       int3 = (third - root) % 12
       int5 = (fifth - root) % 12

       if int3 == 4 and int5 == 7:
           return "maior"
       elif int3 == 3 and int5 == 7:
           return "menor"
       elif int3 == 3 and int5 == 6:
           return "diminuta"
       elif int3 == 4 and int5 == 8:
           return "aumentada"
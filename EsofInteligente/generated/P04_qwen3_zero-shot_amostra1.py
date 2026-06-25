def classificar_triade(notas: list[str]) -> str:
       notas = [n.upper() for n in notas]
       mapa = {
           'C': 0, 'C#': 1, 'DB': 1, 'D': 2, 'D#': 3, 'EB': 3,
           'E': 4, 'FB': 4, 'E#': 5, 'F': 5, 'F#': 6, 'GB': 6,
           'G': 7, 'G#': 8, 'AB': 8, 'A': 9, 'A#': 10, 'BB': 10,
           'B': 11, 'CB': 11, 'B#': 0
       }
       idx = [mapa[n] for n in notas]
       int3 = (idx[1] - idx[0]) % 12
       int5 = (idx[2] - idx[0]) % 12

       if int3 == 4 and int5 == 7:
           return "maior"
       elif int3 == 3 and int5 == 7:
           return "menor"
       elif int3 == 3 and int5 == 6:
           return "diminuta"
       elif int3 == 4 and int5 == 8:
           return "aumentada"
       return ""
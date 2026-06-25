CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

   def classificar_triade(notas: list[str]) -> str:
       i1 = CROMATICA.index(notas[0])
       i2 = CROMATICA.index(notas[1])
       i3 = CROMATICA.index(notas[2])
       int3 = (i2 - i1) % 12
       int5 = (i3 - i1) % 12
       if int3 == 4 and int5 == 7:
           return "maior"
       if int3 == 3 and int5 == 7:
           return "menor"
       if int3 == 3 and int5 == 6:
           return "diminuta"
       if int3 == 4 and int5 == 8:
           return "aumentada"
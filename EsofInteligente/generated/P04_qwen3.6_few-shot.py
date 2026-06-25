CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

   def classificar_triade(notas: list[str]) -> str:
       i1 = CROMATICA.index(notas[0])
       i2 = CROMATICA.index(notas[1])
       i3 = CROMATICA.index(notas[2])
       s1 = (i2 - i1) % 12
       s2 = (i3 - i1) % 12
       if s1 == 4 and s2 == 7: return "maior"
       if s1 == 3 and s2 == 7: return "menor"
       if s1 == 3 and s2 == 6: return "diminuta"
       if s1 == 4 and s2 == 8: return "aumentada"
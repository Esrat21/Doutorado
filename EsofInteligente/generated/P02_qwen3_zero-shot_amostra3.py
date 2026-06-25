def validar_escala_maior(notas: list[str]) -> bool:
         cromatica = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
         if len(notas) != 8:
             return False
         try:
             indices = [cromatica.index(n) for n in notas]
         except ValueError:
             return False
         intervalos = []
         for i in range(7):
             diff = indices[i+1] - indices[i]
             if diff < 0:
                 diff += 12
             intervalos.append(diff)
         return intervalos == [2, 2, 1, 2, 2, 2, 1]
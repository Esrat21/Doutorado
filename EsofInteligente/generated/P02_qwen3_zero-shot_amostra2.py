def validar_escala_maior(notas: list[str]) -> bool:
       cromatica = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']
       if len(notas) != 8:
           return False
       try:
           indices = [cromatica.index(nota) for nota in notas]
       except ValueError:
           return False
       intervalos = [(indices[i+1] - indices[i]) % 12 for i in range(7)]
       return intervalos == [2, 2, 1, 2, 2, 2, 1]
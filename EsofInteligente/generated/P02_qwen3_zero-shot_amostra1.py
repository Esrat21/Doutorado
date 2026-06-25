def validar_escala_maior(notas: list[str]) -> bool:
       cromatica = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
       if len(notas) != 8:
           return False
       escalas = [[cromatica[(i + o) % 12] for o in (0, 2, 4, 5, 7, 9, 11, 12)] for i in range(12)]
       return notas in escalas
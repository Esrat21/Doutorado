CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

   def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       idx = CROMATICA.index(tonalidade)
       intervalos = [0, 2, 4, 5, 7, 9, 11]
       sufixos = ["", "m", "m", "", "", "m", "°"]
       return [CROMATICA[(idx + i) % 12] + s for i, s in zip(intervalos, sufixos)]
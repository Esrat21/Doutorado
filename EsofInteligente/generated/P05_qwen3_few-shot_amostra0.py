CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

   def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       idx = CROMATICA.index(tonalidade)
       return [CROMATICA[(idx + i) % 12] + q for i, q in zip([0, 2, 4, 5, 7, 9, 11], ["", "m", "m", "", "", "m", "dim"])]
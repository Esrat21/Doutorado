CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


   def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       ton_index = CROMATICA.index(tonalidade)
       graus = [0, 2, 4, 5, 7, 9, 11]
       qualidades = ["", "m", "m", "", "", "m", "dim"]
       return [CROMATICA[(ton_index + g) % 12] + q for g, q in zip(graus, qualidades)]
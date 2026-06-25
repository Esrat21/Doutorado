CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
   INTERVALOS = [0, 2, 4, 5, 7, 9, 11]
   SUFIXOS = ["", "m", "m", "", "", "m", "dim"]

   def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       raiz_idx = CROMATICA.index(tonalidade)
       return [CROMATICA[(raiz_idx + i) % 12] + s for i, s in zip(INTERVALOS, SUFIXOS)]
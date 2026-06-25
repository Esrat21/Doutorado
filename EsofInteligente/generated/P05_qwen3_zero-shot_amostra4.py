def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       notas = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
       raiz_idx = notas.index(tonalidade)
       graus = [0, 2, 4, 5, 7, 9, 11]
       suffixes = ["", "m", "m", "", "", "m", "dim"]
       return [notas[(raiz_idx + g) % 12] + s for g, s in zip(graus, suffixes)]
def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
         notas = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
         nota_idx = {"C":0, "C#":1, "Db":1, "D":2, "D#":3, "Eb":3, "E":4, "F":5, "F#":6, "Gb":6, "G":7, "G#":8, "Ab":8, "A":9, "A#":10, "Bb":10, "B":11}
         base = nota_idx.get(tonalidade.strip().upper(), 0)
         graus = [0, 2, 4, 5, 7, 9, 11]
         sufixos = ["", "m", "m", "", "", "m", "dim"]
         return [notas[(base + g) % 12] + s for g, s in zip(graus, sufixos)]
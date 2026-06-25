CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

     def _intervalo(n1, n2):
         return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12

     def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
         idx = CROMATICA.index(tonalidade)
         # Intervals for major scale degrees relative to tonic
         graus = [0, 2, 4, 5, 7, 9, 11]
         notas_raiz = [CROMATICA[(idx + g) % 12] for g in graus]

         # Qualities: I maj, ii min, iii min, IV maj, V maj, vi min, vii dim
         qualidades = ["", "m", "m", "", "", "m", "dim"]
         return [f"{raiz}{qual}" for raiz, qual in zip(notas_raiz, qualidades)]
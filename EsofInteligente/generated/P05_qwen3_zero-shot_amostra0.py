def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       notas_flat = ["C", "Db", "D", "Eb", "E", "F", "Gb", "G", "Ab", "A", "Bb", "B"]
       notas_sharp = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
       intervalos = [0, 2, 4, 5, 7, 9, 11]
       qualidades = ["", "m", "m", "", "", "m", "dim"]

       tonalidade_norm = tonalidade.strip().title()
       # Handle cases like "Db" -> "Db", "Bb" -> "Bb", "C" -> "C"
       # Actually, title() makes "db" -> "Db", which is fine.
       root_idx = notas_flat.index(tonalidade_norm) if tonalidade_norm in notas_flat else 0

       campo = []
       for i in range(7):
           idx = (root_idx + intervalos[i]) % 12
           nota = notas_sharp[idx]
           qual = qualidades[i]
           campo.append(nota + qual)
       return campo
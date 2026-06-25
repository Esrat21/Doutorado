def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       notas = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
       bemol_para_sustenido = {"Db": "C#", "Eb": "D#", "Gb": "F#", "Ab": "G#", "Bb": "A#"}
       tonalidade = bemol_para_sustenido.get(tonalidade, tonalidade)
       idx = notas.index(tonalidade)
       intervalos = [0, 2, 4, 5, 7, 9, 11]
       qualidades = ["", "m", "m", "", "", "m", "dim"]
       return [f"{notas[(idx + i) % 12]}{qualidades[j]}" for j, i in enumerate(intervalos)]
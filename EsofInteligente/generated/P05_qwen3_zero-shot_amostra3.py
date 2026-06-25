def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
             notas = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B']
             conversao = {'DB': 'C#', 'EB': 'D#', 'GB': 'F#', 'AB': 'G#', 'BB': 'A#'}
             tonalidade = conversao.get(tonalidade.upper(), tonalidade)
             idx = notas.index(tonalidade)
             graus = [0, 2, 4, 5, 7, 9, 11]
             sufixos = ['', 'm', 'm', '', '', 'm', 'dim']
             return [notas[(idx + g) % 12] + s for g, s in zip(graus, sufixos)]
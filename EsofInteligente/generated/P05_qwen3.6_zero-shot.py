def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
       notas = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
       bemol_sustenido = {"DB": "C#", "EB": "D#", "FB": "E", "GB": "F#", "AB": "G#", "BB": "A#", "CB": "B"}
       
       t = tonalidade.strip().upper()
       if t in bemol_sustenido:
           t = bemol_sustenido[t]
           
       idx_inicial = notas.index(t)
       intervalos_maior = [0, 2, 4, 5, 7, 9, 11]
         qualidades = ["", "m", "m", "", "", "m", "°"]
       
       campo = []
       for i in range(7):
           idx_raiz = (idx_inicial + intervalos_maior[i]) % 12
           raiz = notas[idx_raiz]
           acorde = raiz + qualidades[i]
           campo.append(acorde)
       return campo
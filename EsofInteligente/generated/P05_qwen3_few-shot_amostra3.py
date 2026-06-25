CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
SUFIXOS = ["", "m", "m", "", "", "m", "dim"]

def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    idx = CROMATICA.index(tonalidade)
    return [CROMATICA[(idx + i) % 12] + SUFIXOS[i] for i in range(7)]
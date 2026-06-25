CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def calcular_intervalo(nota1: str, nota2: str) -> int:
    try:
        idx1 = CROMATICA.index(nota1)
        idx2 = CROMATICA.index(nota2)
    except ValueError:
        # Trata o caso de notas inválidas, embora não seja estritamente necessário pelo prompt, é boa prática.
        return -1 

    intervalo = (idx2 - idx1) % 12
    return intervalo
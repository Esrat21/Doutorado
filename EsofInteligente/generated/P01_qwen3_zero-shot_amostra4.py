def calcular_intervalo(nota1: str, nota2: str) -> int:
    escala = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    return (escala.index(nota2) - escala.index(nota1)) % 12
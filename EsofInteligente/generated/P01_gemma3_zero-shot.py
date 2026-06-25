def calcular_intervalo(nota1: str, nota2: str) -> int:
    NOTE_MAP = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5,
        "F#": 6, "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }

    if nota1 not in NOTE_MAP or nota2 not in NOTE_MAP:
        raise ValueError("Notas inválidas. Use o sistema cromático padrão.")

    valor1 = NOTE_MAP[nota1]
    valor2 = NOTE_MAP[nota2]

    # Calcula a diferença e usa o módulo 12 para garantir o comportamento circular
    intervalo = (valor2 - valor1) % 12
    return intervalo
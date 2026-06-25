def calcular_intervalo(nota1: str, nota2: str) -> int:
    CHROMATIC_SCALE = [
        "C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"
    ]
    NOTE_TO_INDEX = {note: i for i, note in enumerate(CHROMATIC_SCALE)}

    if nota1 not in NOTE_TO_INDEX or nota2 not in NOTE_TO_INDEX:
        raise ValueError("Notas inválidas. Use o formato C, C#, D, etc.")

    index1 = NOTE_TO_INDEX[nota1]
    index2 = NOTE_TO_INDEX[nota2]

    # O cálculo (index2 - index1) % 12 garante a distância ascendente circular em semitons.
    intervalo = (index2 - index1) % 12
    return intervalo
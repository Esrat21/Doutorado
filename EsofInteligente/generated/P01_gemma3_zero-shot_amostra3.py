def calcular_intervalo(nota1: str, nota2: str) -> int:
    chromatic_scale = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    note_map = {nota: i for i, nota in enumerate(chromatic_scale)}

    if nota1 not in note_map or nota2 not in note_map:
        raise ValueError("Notas inválidas fornecidas.")

    index1 = note_map[nota1]
    index2 = note_map[nota2]

    # O cálculo é circular (modulo 12) e representa a distância ascendente.
    intervalo = (index2 - index1) % 12
    return intervalo
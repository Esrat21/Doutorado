def calcular_intervalo(nota1: str, nota2: str) -> int:
    mapa_notas = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5,
        "F#": 6, "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }

    if nota1 not in mapa_notas or nota2 not in mapa_notas:
        raise ValueError("Notas inválidas. Use o formato padrão (ex: 'C', 'F#').")

    indice1 = mapa_notas[nota1]
    indice2 = mapa_notas[nota2]

    # O cálculo circular é feito usando a operação módulo 12.
    intervalo = (indice2 - indice1) % 12
    return intervalo
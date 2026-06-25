def calcular_intervalo(nota1: str, nota2: str) -> int:
    chromatic_scale = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5,
        "F#": 6, "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
    }

    if nota1 not in chromatic_scale or nota2 not in chromatic_scale:
        raise ValueError("Notas inválidas. Use a escala cromática padrão.")

    valor1 = chromatic_scale[nota1]
    valor2 = chromatic_scale[nota2]

    # O cálculo do intervalo ascendente em semitons, usando o módulo 12 para circularidade.
    intervalo = (valor2 - valor1) % 12
    return intervalo
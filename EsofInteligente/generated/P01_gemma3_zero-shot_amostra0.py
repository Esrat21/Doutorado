def calcular_intervalo(nota1: str, nota2: str) -> int:
    cromatica = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    mapa_notas = {nota: i for i, nota in enumerate(cromatica)}

    if nota1 not in mapa_notas or nota2 not in mapa_notas:
        raise ValueError("Notas inválidas. Use o sistema cromático padrão.")

    indice1 = mapa_notas[nota1]
    indice2 = mapa_notas[nota2]

    # O cálculo é (índice final - índice inicial) módulo 12
    intervalo = (indice2 - indice1) % 12
    return intervalo
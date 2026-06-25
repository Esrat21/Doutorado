CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

def classificar_triade(notas: list[str]) -> str:
    if len(notas) != 3:
        return "Triade inválida"

    # Garante que as notas estejam em ordem crescente de semitons para cálculo correto dos intervalos
    notas_ordenadas = sorted(notas, key=lambda n: CROMATICA.index(n))

    try:
        idx1 = CROMATICA.index(notas_ordenadas[0])
        idx2 = CROMATICA.index(notas_ordenadas[1])
        idx3 = CROMATICA.index(notas_ordenadas[2])
    except ValueError:
        return "Nota inválida"

    # Cálculo dos intervalos em semitons (relativo à tônica)
    intervalo_terca = (idx2 - idx1) % 12
    intervalo_quinta = (idx3 - idx1) % 12

    # Classificação baseada nos padrões de semitons: [Terca, Quinta]
    if intervalo_terca == 4 and intervalo_quinta == 7:
        return "maior"  # M3=4, P5=7
    elif intervalo_terca == 3 and intervalo_quinta == 7:
        return "menor" # m3=3, P5=7
    elif intervalo_terca == 3 and intervalo_quinta == 6:
        return "diminuta" # d3=3, d5=6
    elif intervalo_terca == 4 and intervalo_quinta == 8:
        return "aumentada" # A3=4, A5=8
    else:
        # Caso não se encaixe em nenhum padrão comum (ex: tríade alterada ou dissonante)
        return "indefinida"
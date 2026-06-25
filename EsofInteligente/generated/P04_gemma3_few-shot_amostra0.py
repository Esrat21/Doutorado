CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]


def classificar_triade(notas: list[str]) -> str:
    if len(notas) != 3:
        return ""

    try:
        idx_root = CROMATICA.index(notas[0].upper())
        idx2 = CROMATICA.index(notas[1].upper())
        idx3 = CROMATICA.index(notas[2].upper())
    except ValueError:
        return "Notas inválidas"

    # Calcula os intervalos em semitons (distância modular)
    intervalo_2 = (idx2 - idx_root) % 12
    intervalo_3 = (idx3 - idx_root) % 12

    # Coleta e ordena os intervalos não nulos. O intervalo 0 é sempre o root.
    intervals = sorted(list(set([intervalo_2, intervalo_3])))

    if intervals[0] == 0: # Caso em que notas[1] ou notas[2] são iguais à raiz
        pass # Não deve acontecer se a entrada for uma tríade válida e distinta.

    # Garante que os intervalos sejam únicos e ordenados (sem contar o root=0)
    if intervalo_2 == intervalo_3:
        intervals = [intervalo_2]
    else:
        intervals = sorted([intervalo_2, intervalo_3])

    # Os três passos cromáticos são 0, I1 e I2 (onde I1 < I2)
    passos = [0] + intervals

    # Verifica os padrões de intervalos semitons (Root, Terça, Quinta)
    if passos == [0, 4, 7]:
        return "maior"
    elif passos == [0, 3, 7]:
        return "menor"
    elif passos == [0, 3, 6]:
        return "diminuta"
    elif passos == [0, 4, 8]:
        return "aumentada"
    else:
        # Se os intervalos não corresponderem a um padrão conhecido (ex: [0, 5, 7])
        return ""<unused56>
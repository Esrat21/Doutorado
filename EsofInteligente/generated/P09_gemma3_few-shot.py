def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    if len(acorde1) != 4 or len(acorde2) != 4:
        return False

    for i in range(4):
        movimento = abs(acorde2[i] - acorde1[i])
        if movimento > 5:
            return False
    return True
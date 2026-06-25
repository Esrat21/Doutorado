def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    for i in range(4):
        movimento = abs(acorde1[i] - acorde2[i])
        if movimento > 5:
            return False
    return True
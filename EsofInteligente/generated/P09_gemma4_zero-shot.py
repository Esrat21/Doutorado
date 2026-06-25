def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    for p1, p2 in zip(acorde1, acorde2):
        movimento = abs(p1 - p2)
        if movimento > 5:
            return False
    return True
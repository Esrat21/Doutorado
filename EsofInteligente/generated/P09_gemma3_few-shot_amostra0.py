def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    for c1, c2 in zip(acorde1, acorde2):
        if abs(c2 - c1) > 5:
            return False
    return True
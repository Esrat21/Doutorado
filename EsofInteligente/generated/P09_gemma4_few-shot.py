def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    for a1, a2 in zip(acorde1, acorde2):
        if abs(a2 - a1) > 5:
            return False
    return True
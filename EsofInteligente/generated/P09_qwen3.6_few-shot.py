def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    for v1, v2 in zip(acorde1, acorde2):
        if abs(v1 - v2) > 5:
            return False
    return True
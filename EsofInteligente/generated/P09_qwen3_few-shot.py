def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    for n1, n2 in zip(acorde1, acorde2):
        if abs(n1 - n2) > 5:
            return False
    return True
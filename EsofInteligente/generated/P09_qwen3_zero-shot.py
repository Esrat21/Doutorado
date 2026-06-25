def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    for a, b in zip(acorde1, acorde2):
        if min(abs(a - b), 12 - abs(a - b)) > 5:
            return False
    return True
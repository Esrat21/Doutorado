def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    return all(abs(a2 - a1) <= 5 for a1, a2 in zip(acorde1, acorde2))
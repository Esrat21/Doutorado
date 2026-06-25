def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    return all(abs(a - b) <= 5 for a, b in zip(acorde1, acorde2))
def verificar_conducao_vozes(acorde1: list[int], acorde2: list[int]) -> bool:
    if len(acorde1) != len(acorde2):
        return False
    for h1, h2 in zip(acorde1, acorde2):
        movimento = abs(h2 - h1)
        if movimento > 5:
            return False
    return True
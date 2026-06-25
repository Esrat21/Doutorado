def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    if graus[-1] != 'I':
        return False

    preceding_degrees = graus[:-1]

    if not preceding_degrees:
        return False

    has_iv_or_v = ('IV' in preceding_degrees) or ('V' in preceding_degrees)

    return has_iv_or_v
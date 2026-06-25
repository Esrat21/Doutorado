def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # Rule 1: Must end in I
    ends_in_I = (graus[-1] == 'I')

    # Rule 2: Must contain IV or V before the final resolution
    preceding_degrees = graus[:-1]
    has_iv_or_v = ('IV' in preceding_degrees) or ('V' in preceding_degrees)

    return ends_in_I and has_iv_or_v
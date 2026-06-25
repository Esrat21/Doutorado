def validar_progressao(graus: list[str]) -> bool:
    if not graus:
        return False

    # 1. Deve terminar em I
    if graus[-1] != 'I':
        return False

    # 2. Deve ter pelo menos um IV ou V antes da resolução final (todos os elementos exceto o último)
    pre_resolution = graus[:-1]

    has_iv_or_v = 'IV' in pre_resolution or 'V' in pre_resolution

    return has_iv_or_v
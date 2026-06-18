from solution import verificar_conducao_vozes


def test_conducao_valida_movimentos_pequenos():
    assert verificar_conducao_vozes([60, 64, 67, 72], [62, 65, 69, 74]) is True


def test_conducao_valida_movimento_limite_5():
    assert verificar_conducao_vozes([60, 64, 67, 72], [65, 69, 72, 77]) is True


def test_conducao_invalida_uma_voz_maior_que_5():
    assert verificar_conducao_vozes([60, 64, 67, 72], [66, 64, 67, 72]) is False


def test_conducao_invalida_varias_vozes():
    assert verificar_conducao_vozes([60, 64, 67, 72], [70, 72, 75, 84]) is False


def test_conducao_valida_movimento_descendente():
    assert verificar_conducao_vozes([65, 69, 72, 77], [60, 64, 67, 72]) is True


def test_conducao_sem_movimento():
    assert verificar_conducao_vozes([60, 64, 67, 72], [60, 64, 67, 72]) is True

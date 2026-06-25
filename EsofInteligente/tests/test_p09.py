from solution import verificar_conducao_vozes


def test_conducao_suave():
    assert verificar_conducao_vozes([60, 64, 67, 71], [60, 65, 67, 72]) is True

def test_conducao_identica():
    assert verificar_conducao_vozes([60, 64, 67, 71], [60, 64, 67, 71]) is True

def test_conducao_exatamente_no_limite():
    assert verificar_conducao_vozes([60, 64, 67, 71], [60, 69, 67, 71]) is True

def test_conducao_excede_limite():
    assert verificar_conducao_vozes([60, 64, 67, 71], [60, 70, 67, 71]) is False

def test_conducao_uma_voz_grande_salto():
    assert verificar_conducao_vozes([48, 52, 55, 59], [48, 52, 55, 71]) is False

def test_conducao_movimento_descendente():
    assert verificar_conducao_vozes([60, 64, 67, 71], [60, 60, 67, 71]) is True

from solution import validar_escala_maior


def test_escala_c_maior_valida():
    assert validar_escala_maior(["C", "D", "E", "F", "G", "A", "B", "C"]) is True

def test_escala_g_maior_valida():
    assert validar_escala_maior(["G", "A", "B", "C", "D", "E", "F#", "G"]) is True

def test_escala_tamanho_invalido():
    assert validar_escala_maior(["C", "D", "E", "F", "G", "A", "B"]) is False

def test_escala_com_nove_notas():
    assert validar_escala_maior(["C", "D", "E", "F", "G", "A", "B", "C", "D"]) is False

def test_escala_menor_nao_valida():
    assert validar_escala_maior(["A", "B", "C", "D", "E", "F", "G", "A"]) is False

def test_escala_com_sustenidos_multiplos():
    assert validar_escala_maior(["D", "E", "F#", "G", "A", "B", "C#", "D"]) is True

def test_escala_ordem_errada():
    assert validar_escala_maior(["C", "E", "D", "F", "G", "A", "B", "C"]) is False

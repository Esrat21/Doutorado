from solution import validar_escala_maior


def test_c_maior():
    assert validar_escala_maior(["C", "D", "E", "F", "G", "A", "B", "C"]) is True


def test_g_maior():
    assert validar_escala_maior(["G", "A", "B", "C", "D", "E", "F#", "G"]) is True


def test_d_maior():
    assert validar_escala_maior(["D", "E", "F#", "G", "A", "B", "C#", "D"]) is True


def test_escala_invalida():
    assert validar_escala_maior(["C", "D", "D#", "F", "G", "A", "B", "C"]) is False


def test_lista_incompleta():
    assert validar_escala_maior(["C", "D", "E"]) is False


def test_notas_iguais():
    assert validar_escala_maior(["C", "C", "C", "C", "C", "C", "C", "C"]) is False

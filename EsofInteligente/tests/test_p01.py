from solution import calcular_intervalo


def test_intervalo_c_g():
    assert calcular_intervalo("C", "G") == 7


def test_intervalo_c_e():
    assert calcular_intervalo("C", "E") == 4


def test_intervalo_c_c():
    assert calcular_intervalo("C", "C") == 0


def test_intervalo_b_c():
    assert calcular_intervalo("B", "C") == 1


def test_intervalo_g_c():
    assert calcular_intervalo("G", "C") == 5


def test_intervalo_com_sustenido():
    assert calcular_intervalo("C#", "F#") == 5

from solution import calcular_intervalo


def test_intervalo_c_para_e():
    assert calcular_intervalo("C", "E") == 4

def test_intervalo_c_para_g():
    assert calcular_intervalo("C", "G") == 7

def test_intervalo_iguais():
    assert calcular_intervalo("D", "D") == 0

def test_intervalo_b_para_c():
    assert calcular_intervalo("B", "C") == 1

def test_intervalo_g_para_c():
    assert calcular_intervalo("G", "C") == 5

def test_intervalo_descendente_circular():
    assert calcular_intervalo("A", "C") == 3

def test_intervalo_sustenidos():
    assert calcular_intervalo("F#", "A#") == 4

def test_intervalo_volta_completa():
    assert calcular_intervalo("C", "B") == 11

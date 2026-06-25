from solution import detectar_modulacao


def test_modulacao_detectada():
    assert detectar_modulacao(["C", "C", "G", "G"]) is True

def test_sem_modulacao():
    assert detectar_modulacao(["C", "C", "C", "C"]) is False

def test_modulacao_lista_curta():
    assert detectar_modulacao(["C"]) is False

def test_modulacao_lista_vazia():
    assert detectar_modulacao([]) is False

def test_modulacao_multiplas_tonalidades():
    assert detectar_modulacao(["C", "G", "D", "A"]) is True

def test_modulacao_volta_a_original():
    assert detectar_modulacao(["C", "G", "C"]) is True

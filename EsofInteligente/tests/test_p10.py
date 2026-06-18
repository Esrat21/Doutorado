from solution import detectar_modulacao


def test_sem_modulacao():
    assert detectar_modulacao(["C", "C", "C", "C"]) is False


def test_com_modulacao_simples():
    assert detectar_modulacao(["C", "C", "G", "G"]) is True


def test_com_modulacao_imediata():
    assert detectar_modulacao(["C", "G"]) is True


def test_todas_diferentes():
    assert detectar_modulacao(["C", "G", "D", "A"]) is True


def test_lista_com_um_elemento():
    assert detectar_modulacao(["C"]) is False


def test_lista_vazia():
    assert detectar_modulacao([]) is False

from solution import validar_progressao


def test_progressao_iv_v_i():
    assert validar_progressao(["I", "IV", "V", "I"]) is True


def test_progressao_vi_iv_v_i():
    assert validar_progressao(["vi", "IV", "V", "I"]) is True


def test_progressao_ii_v_i():
    assert validar_progressao(["ii", "V", "I"]) is True


def test_nao_termina_em_i():
    assert validar_progressao(["I", "IV", "V"]) is False


def test_sem_iv_ou_v_antes_do_i():
    assert validar_progressao(["I", "vi", "iii", "I"]) is False


def test_lista_vazia():
    assert validar_progressao([]) is False

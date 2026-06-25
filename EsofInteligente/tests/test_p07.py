from solution import validar_progressao


def test_progressao_valida_com_v():
    assert validar_progressao(["I", "IV", "V", "I"]) is True

def test_progressao_valida_com_iv():
    assert validar_progressao(["I", "IV", "I"]) is True

def test_progressao_sem_resolucao():
    assert validar_progressao(["I", "IV", "V"]) is False

def test_progressao_sem_iv_ou_v():
    assert validar_progressao(["I", "vi", "I"]) is False

def test_progressao_vazia():
    assert validar_progressao([]) is False

def test_progressao_longa_valida():
    assert validar_progressao(["I", "vi", "IV", "V", "vi", "IV", "V", "I"]) is True

def test_progressao_termina_errado():
    assert validar_progressao(["I", "IV", "V", "vi"]) is False

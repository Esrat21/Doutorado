from solution import detectar_inversao


def test_estado_fundamental_maior():
    assert detectar_inversao(["C", "E", "G"]) == "estado fundamental"

def test_estado_fundamental_menor():
    assert detectar_inversao(["A", "C", "E"]) == "estado fundamental"

def test_primeira_inversao():
    assert detectar_inversao(["E", "G", "C"]) == "primeira inversao"

def test_segunda_inversao():
    assert detectar_inversao(["G", "C", "E"]) == "segunda inversao"

def test_primeira_inversao_menor():
    assert detectar_inversao(["C", "E", "A"]) == "primeira inversao"

def test_segunda_inversao_outra_triade():
    assert detectar_inversao(["D", "G", "B"]) == "segunda inversao"

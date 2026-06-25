from solution import classificar_triade


def test_triade_maior():
    assert classificar_triade(["C", "E", "G"]) == "maior"

def test_triade_menor():
    assert classificar_triade(["C", "D#", "G"]) == "menor"

def test_triade_diminuta():
    assert classificar_triade(["C", "D#", "F#"]) == "diminuta"

def test_triade_aumentada():
    assert classificar_triade(["C", "E", "G#"]) == "aumentada"

def test_triade_invalida():
    assert classificar_triade(["C", "D", "F#"]) == "desconhecida"

def test_triade_maior_outra_tonica():
    assert classificar_triade(["G", "B", "D"]) == "maior"

def test_triade_menor_com_sustenido():
    assert classificar_triade(["F#", "A", "C#"]) == "menor"

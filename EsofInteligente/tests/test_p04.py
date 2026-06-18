from solution import classificar_triade


def test_triade_maior_c():
    assert classificar_triade(["C", "E", "G"]) == "maior"


def test_triade_menor_c():
    assert classificar_triade(["C", "D#", "G"]) == "menor"


def test_triade_diminuta_c():
    assert classificar_triade(["C", "D#", "F#"]) == "diminuta"


def test_triade_aumentada_c():
    assert classificar_triade(["C", "E", "G#"]) == "aumentada"


def test_triade_maior_g():
    assert classificar_triade(["G", "B", "D"]) == "maior"


def test_triade_menor_a():
    assert classificar_triade(["A", "C", "E"]) == "menor"

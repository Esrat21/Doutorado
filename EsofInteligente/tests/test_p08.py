from solution import classificar_tetrade


def test_tetrade_maior7():
    assert classificar_tetrade(["C", "E", "G", "B"]) == "maior7"

def test_tetrade_dominante7():
    assert classificar_tetrade(["C", "E", "G", "A#"]) == "dominante7"

def test_tetrade_menor7():
    assert classificar_tetrade(["C", "D#", "G", "A#"]) == "menor7"

def test_tetrade_meio_diminuto():
    assert classificar_tetrade(["C", "D#", "F#", "A#"]) == "meio_diminuto"

def test_tetrade_invalida():
    assert classificar_tetrade(["C", "D", "F#", "A"]) == "desconhecida"

def test_tetrade_maior7_outra_tonica():
    assert classificar_tetrade(["G", "B", "D", "F#"]) == "maior7"

def test_tetrade_dominante7_outra_tonica():
    assert classificar_tetrade(["G", "B", "D", "F"]) == "dominante7"

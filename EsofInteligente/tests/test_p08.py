from solution import classificar_tetrade


def test_tetrade_maior7_c():
    assert classificar_tetrade(["C", "E", "G", "B"]) == "maior7"


def test_tetrade_dominante7_c():
    assert classificar_tetrade(["C", "E", "G", "A#"]) == "dominante7"


def test_tetrade_menor7_c():
    assert classificar_tetrade(["C", "D#", "G", "A#"]) == "menor7"


def test_tetrade_meio_diminuto_c():
    assert classificar_tetrade(["C", "D#", "F#", "A#"]) == "meio_diminuto"


def test_tetrade_maior7_g():
    assert classificar_tetrade(["G", "B", "D", "F#"]) == "maior7"


def test_tetrade_menor7_a():
    assert classificar_tetrade(["A", "C", "E", "G"]) == "menor7"

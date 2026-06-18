from solution import cifra_para_semitom


def test_c():
    assert cifra_para_semitom("C") == 0


def test_c_sustenido():
    assert cifra_para_semitom("C#") == 1


def test_e():
    assert cifra_para_semitom("E") == 4


def test_f_sustenido():
    assert cifra_para_semitom("F#") == 6


def test_a_sustenido():
    assert cifra_para_semitom("A#") == 10


def test_b():
    assert cifra_para_semitom("B") == 11

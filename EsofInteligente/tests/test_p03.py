from solution import cifra_para_semitom


def test_cifra_c():
    assert cifra_para_semitom("C") == 0

def test_cifra_g():
    assert cifra_para_semitom("G") == 7

def test_cifra_b():
    assert cifra_para_semitom("B") == 11

def test_cifra_sustenido_a():
    assert cifra_para_semitom("A#") == 10

def test_cifra_sustenido_c():
    assert cifra_para_semitom("C#") == 1

def test_cifra_todas_sequencial():
    notas = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    valores = [cifra_para_semitom(n) for n in notas]
    assert valores == list(range(12))

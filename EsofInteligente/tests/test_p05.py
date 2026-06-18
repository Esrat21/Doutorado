from solution import gerar_campo_harmonico_maior


def test_campo_harmonico_c_maior():
    assert gerar_campo_harmonico_maior("C") == [
        "C", "Dm", "Em", "F", "G", "Am", "Bdim"
    ]


def test_campo_harmonico_g_maior():
    assert gerar_campo_harmonico_maior("G") == [
        "G", "Am", "Bm", "C", "D", "Em", "F#dim"
    ]


def test_campo_harmonico_d_maior():
    assert gerar_campo_harmonico_maior("D") == [
        "D", "Em", "F#m", "G", "A", "Bm", "C#dim"
    ]


def test_campo_harmonico_f_maior():
    assert gerar_campo_harmonico_maior("F") == [
        "F", "Gm", "Am", "A#", "C", "Dm", "Edim"
    ]


def test_retorna_sete_acordes():
    assert len(gerar_campo_harmonico_maior("C")) == 7

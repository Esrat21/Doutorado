from solution import gerar_campo_harmonico_maior


def test_campo_c_maior():
    esperado = ["C", "Dm", "Em", "F", "G", "Am", "Bdim"]
    assert gerar_campo_harmonico_maior("C") == esperado

def test_campo_g_maior():
    esperado = ["G", "Am", "Bm", "C", "D", "Em", "F#dim"]
    assert gerar_campo_harmonico_maior("G") == esperado

def test_campo_comeca_com_sustenido():
    campo = gerar_campo_harmonico_maior("F#")
    assert campo[0] == "F#"
    assert len(campo) == 7

def test_campo_b_maior_apenas_sustenidos():
    campo = gerar_campo_harmonico_maior("B")
    assert all("b" not in acorde for acorde in campo)
    assert campo[0] == "B"

def test_campo_tamanho_sempre_sete():
    for tonica in ["C", "D", "E", "F", "G", "A", "B"]:
        assert len(gerar_campo_harmonico_maior(tonica)) == 7

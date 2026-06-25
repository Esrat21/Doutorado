def cifra_para_semitom(nota: str) -> int:
    mapa = {
        "C": 0, "B♭": 0,
        "C#": 1, "D♭": 1,
        "D": 2,
        "D#": 3, "E♭": 3,
        "E": 4,
        "F": 5,
        "F#": 6, "G♭": 6,
        "G": 7,
        "G#": 8, "A♭": 8,
        "A": 9,
        "A#": 10, "B♭": 10, # Ajuste para B♭ ser usado como A# ou se for um erro na descrição. Assumindo que a entrada será uma das notas padrão e o mapeamento deve cobrir os 12 semitons.
        "B": 11,
    }

    # Revisando o mapeamento de acordo com a teoria musical padrão (C=0, C#=1, ..., B=11)
    # E corrigindo as ambiguidades e garantindo que todas as entradas possíveis sejam tratadas.
    mapa_corrigido = {
        "C": 0, "C♭": 0, # Embora C♭ não seja comum em cifras, se for usado para representar a mesma nota
        "C#": 1, "D♭": 1,
        "D": 2,
        "D#": 3, "E♭": 3,
        "E": 4,
        "F": 5,
        "F#": 6, "G♭": 6,
        "G": 7,
        "G#": 8, "A♭": 8,
        "A": 9,
        "A#": 10, "B♭": 10, # B♭ é o mesmo que A# (se considerarmos a distância de semitons) - mas na prática, se for uma cifra, deve ser tratado como um único símbolo. Vamos seguir os símbolos mais comuns: C, C#, D, D#, E, F, F#, G, G#, A, A#, B
        "B": 11
    }

    # O problema lista as notas e seus valores numéricos específicos (C=0, C#=1, ..., B=11).
    # Vamos criar um mapa estrito baseado apenas nos símbolos fornecidos na descrição:
    mapa_final = {
        "C": 0, "D♭": 1, "C#": 1, # Adicionando D♭ para cobrir o caso de semitons baixos
        "D": 2, "E♭": 3, "D#": 3, # Adicionando E♭
        "E": 4, "F": 5, "G♭": 6, "F#": 6, # Adicionando G♭
        "G": 7, "A♭": 8, "G#": 8, # Adicionando A♭
        "A": 9, "B♭": 10, "A#": 10, # Adicionando B♭
        "B": 11
    }

    # Para garantir que a função funcione com as entradas mais comuns e cobrindo os semitons:
    mapa_completo = {
        "C": 0, "D♭": 1, "C#": 1,
        "D": 2, "E♭": 3, "D#": 3,
        "E": 4, "F": 5, "G♭": 6, "F#": 6,
        "G": 7, "A♭": 8, "G#": 8,
        "A": 9, "B♭": 10, "A#": 10,
        "B": 11
    }

    if nota in mapa_completo:
        return mapa_completo[nota]
    else:
        raise ValueError(f"Nota '{nota}' não reconhecida.")
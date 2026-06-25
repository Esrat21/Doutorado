def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    NOTES = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

    if tonalidade not in NOTES:
        raise ValueError("Tonalidade inválida. Use apenas notas com sustenido.")

    root_index = NOTES.index(tonalidade)
    
    def get_note(semitones):
        return NOTES[(root_index + semitones) % 12]

    # Intervalos em semitons (em relação à tônica)
    # I: Root, M3 (+4), P5 (+7) -> Maior
    i = [get_note(0), get_note(4), get_note(7)]
    
    # ii: Root, m3 (+3), P5 (+9) -> Menor
    ii = [get_note(2), get_note(5), get_note(9)]

    # iii: Root, m3 (+4), P5 (+8) -> Menor
    iii = [get_note(4), get_note(7), get_note(11)]

    # IV: Root, M3 (+5), P5 (+7) -> Maior
    iv = [get_note(5), get_note(9), get_note(0)]

    # V: Root, M3 (+7), P5 (+2) -> Maior
    v = [get_note(7), get_note(11), get_note(2)]

    # vi: Root, m3 (+9), P5 (+4) -> Menor
    vi = [get_note(9), get_note(0), get_note(4)]

    # vii°: Root, m3 (+10), dim5 (+7+3=10) -> Diminuto
    vii = [get_note(11), get_note(2), get_note(5)]


    def formatar_acorde(intervalos):
        return f"{intervalos[0]} - {intervalos[1]} - {intervalos[2]}"

    campo_harmonico = [
        formatar_acorde(i) + " (Maior)",
        formatar_acorde(ii) + " (Menor)",
        formatar_acorde(iii) + " (Menor)",
        formatar_acorde(iv) + " (Maior)",
        formatar_acorde(v) + " (Maior)",
        formatar_acorde(vi) + " (Menor)",
        formatar_acorde(vii) + " (Diminuto)"
    ]

    return campo_harmonico
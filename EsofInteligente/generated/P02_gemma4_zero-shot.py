def validar_escala_maior(notas: list[str]) -> bool:
    CHROMATIC = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    SEMITONE_MAP = {note.lower(): i for i, note in enumerate(CHROMATIC)}

    if len(notas) != 8:
        return False

    try:
        semitones = []
        for nota in notas:
            nota_lower = nota.strip().lower()
            if nota_lower not in SEMITONE_MAP:
                return False  # Nota inválida
            semitones.append(SEMITONE_MAP[nota_lower])
    except Exception:
        return False

    intervalos = []
    for i in range(7):
        # Calcula o intervalo em semitons, garantindo que seja positivo (modulo 12)
        intervalo = (semitones[i+1] - semitones[i]) % 12
        intervalos.append(intervalo)

    PADRAO_ESCALA_MAIOR = [2, 2, 1, 2, 2, 2, 1]

    return intervalos == PADRAO_ESCALA_MAIOR
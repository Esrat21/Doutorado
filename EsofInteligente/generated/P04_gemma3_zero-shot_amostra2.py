def classificar_triade(notas: list[str]) -> str:
    PITCH_MAP = {
        "C": 0, "B#": 0,
        "C#": 1, "Db": 1,
        "D": 2,
        "D#": 3, "Eb": 3,
        "E": 4,
        "F": 5, "E#": 5,
        "F#": 6, "Gb": 6,
        "G": 7,
        "G#": 8, "Ab": 8,
        "A": 9,
        "A#": 10, "Bb": 10,
        "B": 11,
    }

    def get_pitch(note: str) -> int:
        if note not in PITCH_MAP:
            raise ValueError(f"Nota desconhecida: {note}")
        return PITCH_MAP[note]

    try:
        p1 = get_pitch(notas[0])
        p2 = get_pitch(notas[1])
        p3 = get_pitch(notas[2])
    except ValueError as e:
        return "Erro de entrada"

    # Calcula os intervalos em semitons (distância absoluta)
    # O módulo 12 garante que o intervalo seja dentro da mesma oitava.
    intervalo_2 = abs(p2 - p1) % 12
    intervalo_3 = abs(p3 - p1) % 12

    # Coleta os intervalos não nulos e ordena-os para comparação consistente
    intervals = sorted([i for i in [intervalo_2, intervalo_3] if i != 0])

    if len(intervals) < 2:
        return "Triade inválida (notas repetidas)"

    d1 = intervals[0]
    d2 = intervals[1]

    # Classificação baseada nos intervalos semitonais do Root (p1)
    # Padrões esperados (Root, Terça, Quinta):
    # Maior: (4, 7)
    # Menor: (3, 7)
    # Diminuta: (3, 6)
    # Aumentada: (4, 8)

    if d1 == 3 and d2 == 7:
        return "menor"
    elif d1 == 4 and d2 == 7:
        return "maior"
    elif d1 == 3 and d2 == 6:
        return "diminuta"
    elif d1 == 4 and d2 == 8:
        return "aumentada"
    else:
        # Se não corresponder a nenhum padrão clássico, pode ser um acorde de sétima ou outro tipo.
        return "indefinido"
def classificar_tetrade(notas: list[str]) -> str:
    try:
        pitches = sorted([int(n) for n in notas])
    except ValueError:
        return "Erro: Notas inválidas"

    if len(pitches) != 4:
        return "Erro: Deve conter exatamente quatro notas"

    root = pitches[0]
    intervals = set()
    for i in range(1, 4):
        interval = pitches[i] - root
        intervals.add(interval)

    # Comparação dos conjuntos de intervalos (semitons a partir da tônica)
    if intervals == {4, 7, 11}:
        return "maior7"
    elif intervals == {3, 7, 10}:
        return "menor7"
    elif intervals == {4, 7, 10}:
        return "dominante7"
    elif intervals == {3, 6, 10}:
        return "meio_diminuto"
    else:
        return "Tipo desconhecido"
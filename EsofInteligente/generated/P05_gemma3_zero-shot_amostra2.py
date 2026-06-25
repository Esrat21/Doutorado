def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    notes = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    
    if tonalidade not in notes:
        raise ValueError("Tonalidade inválida. Use apenas notas com sustenido.")

    try:
        start_index = notes.index(tonalidade)
    except ValueError:
        return [] # Should be caught by the check above, but good practice

    # Os graus da escala maior em semitons (0=tônica)
    # I, ii, iii, IV, V, vi, vii°
    degrees = [0, 2, 4, 5, 7, 9, 11]
    
    # Qualidades dos acordes
    qualities = ["maj", "min", "min", "maj", "maj", "min", "dim"]

    campo_harmonico = []

    for i in range(7):
        degree = degrees[i]
        quality = qualities[i]
        
        # Calcula o índice da nota raiz para este grau, usando módulo 12
        root_index = (start_index + degree) % 12
        root_note = notes[root_index]
        
        chord_name = f"{root_note}{quality}"
        campo_harmonico.append(chord_name)

    return campo_harmonico
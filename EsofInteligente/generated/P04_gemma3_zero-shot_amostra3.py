import collections

def classificar_triade(notas: list[str]) -> str:
    note_map = {
        "C": 0, "B#": 1, "D": 2, "E": 3, "F": 4, "G": 5, "G#": 6, "A": 7, "A#": 8, "B": 9, "C#": 10, "D#": 11
    }

    def get_semitones(note: str) -> int:
        return note_map.get(note.upper(), -1)

    if len(notas) != 3:
        raise ValueError("A lista deve conter exatamente três notas.")

    try:
        root = get_semitones(notas[0])
        n2 = get_semitones(notas[1])
        n3 = get_semitones(notas[2])
    except TypeError:
        return "Notas inválidas"

    def calculate_interval(start_note_val, end_note_val) -> int:
        diff = abs(end_note_val - start_note_val)
        # Retorna o menor intervalo em semitons (dentro de um ciclo de 12 notas)
        return min(diff, 12 - diff)

    intervalos = set()
    
    d2 = calculate_interval(root, n2)
    d3 = calculate_interval(root, n3)

    intervalos.add(d2)
    intervalos.add(d3)

    # Ordena os intervalos para facilitar a comparação (ex: {3, 7} em vez de {7, 3})
    intervals_list = sorted(list(intervalos))

    if len(intervals_list) < 2:
        return "Não forma uma tríade válida" # Caso as notas sejam iguais ou muito próximas

    # Verifica os padrões de intervalos (em semitons):
    # Major: {4, 7} (M3, P5)
    # Minor: {3, 7} (m3, P5)
    # Diminished: {3, 6} (m3, dim5)
    # Augmented: {4, 8} (M3, aug5)

    if intervals_list == [3, 7]:
        return "menor"
    elif intervals_list == [4, 7]:
        return "maior"
    elif intervals_list == [3, 6]:
        return "diminuta"
    elif intervals_list == [4, 8]:
        return "aumentada"
    else:
        # Caso o intervalo seja {5, 7} (Quartal/Terça e Quinta) ou outro padrão não listado.
        return "indefinida"<unused56>
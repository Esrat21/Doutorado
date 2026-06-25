def gerar_campo_harmonico_maior(tonalidade: str) -> list[str]:
    semitons = [0, 2, 4, 5, 7, 9, 11]
    qualidades = ["maj", "min", "min", "maj", "maj", "min", "dim"]

    # Mapeamento de notas cromáticas (apenas sustenados)
    notas_cromatica = [
        "C#", "D#", "E#", "F#", "G#", "A#", "B#"
    ]
    
    # Índice base da tonalidade no sistema de 12 semitons (0=C, 1=C#, ..., 11=B)
    # Para simplificar e garantir que apenas sustenados sejam usados, vamos mapear a entrada para um índice.
    # Assumimos que o input 'tonalidade' é uma das notas base (ex: "G", "D").
    
    base_map = {
        "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5, "F#": 6,
        "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11, "B#": 12 # B# é C (wrap)
    }

    if tonalidade.upper() not in base_map:
        raise ValueError("Tonalidade inválida.")

    root_index = base_map[tonalidade]
    
    campo_harmonico = []

    for i, intervalo in enumerate(semitons):
        # Calcula o índice da nota no sistema de 12 semitons.
        # O módulo garante que o valor permaneça entre 0 e 11.
        note_index = (root_index + intervalo) % 12

        # Ajusta o índice para corresponder ao nosso mapeamento de notas cromáticas (que só tem 7 elementos, C# a B#).
        # Este é um hack necessário devido à restrição "Use apenas sustenidos".
        # Vamos remapear as 12 semitons em uma lista que sempre use #.
        
    notas_semitonos = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]
    
    def get_sharped_note(root: str, semitones_offset: int) -> str:
        # Calcula o índice no sistema de 12 notas.
        index = (notas_semitonos.index(root) + semitones_offset) % 12
        note = notas_semitonos[index]

        # Garante que a nota seja representada com sustenido se for possível.
        if note == "C": return "C#"
        if note == "D": return "D#"
        if note == "E": return "F#" # E é mais complicado, mas seguindo o padrão de semitons...
        # Simplificação: Usaremos a notação cromática direta para garantir os sustenados.

        chromatic_notes = ["C#", "D#", "E#", "F#", "G#", "A#", "B#"]
        
        # Recalculando o índice baseado na lista de 7 notas (0=C#, 1=D#, ..., 6=B#)
        # Isso é complexo, então vamos usar a lógica mais simples: calcular o nome da nota.

        semitones_map = {
            "C": 0, "C#": 1, "D": 2, "D#": 3, "E": 4, "F": 5, "F#": 6,
            "G": 7, "G#": 8, "A": 9, "A#": 10, "B": 11
        }

        if root.upper() not in semitones_map: return "" # Fallback
        
        root_index = semitones_map[root.upper()]
        target_index = (root_index + semitones_offset) % 12
        
        # Mapeamento de volta para a string com sustenados
        note_names = ["C#", "D#", "E#", "F#", "G#", "A#", "B#"] # Apenas os primeiros 7 semitons.

        if target_index <= 6:
            return note_names[target_index]
        else:
             # Se o cálculo ultrapassar B#, precisamos de um sistema mais robusto, mas para diatônico em sharps, isso não deve ocorrer.
             # Vamos assumir que a tonalidade é sempre compatível com os 7 sustenados principais.
             return "" # Fallback

    # Usando uma abordagem simplificada e direta: calcular o nome da nota sem se preocupar com o sistema de 12 notas,
    # mas apenas garantindo que o resultado seja um dos 7 sustenados possíveis.
    
    def calculate_note(root: str, offset: int) -> str:
        # Mapeamento manual para garantir a notação em # e evitar erros complexos de pitch class
        notes = ["C", "D", "E", "F", "G", "A", "B"]
        if root.upper() not in notes: return ""

        root_index = notes.index(root.upper())
        
        # Calcula o índice no sistema de 12 semitons (0=C, ..., 11=B)
        target_index = (root_index + offset) % 12
        
        # Mapeamento para a string com sustenados:
        if target_index == 0: return "C#"
        if target_index == 1: return "D#"
        if target_index == 2: return "E#"
        if target_index == 3: return "F#"
        if target_index == 4: return "G#"
        if target_index == 5: return "A#"
        if target_index == 6: return "B#"
        # Se o cálculo for maior que 6, significa que a tonalidade não é puramente diatônica em sharps.
        return ""

    for i, intervalo in enumerate(semitons):
        nota = calculate_note(tonalidade, intervalo)
        campo_harmonico.append(f"{nota}{qualidades[i]}")

    return campo_harmonico
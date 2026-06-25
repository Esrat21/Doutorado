def detectar_inversao(notas: list[str]) -> str:
    NOTE_VALUES = {
        "C": 0, "B#": 0, "Db": 0, "D": 2, "Eb": 3, "D#": 3, "E": 4, "F": 5,
        "Gb": 6, "F#": 6, "G": 7, "Ab": 8, "G#": 8, "A": 9, "Bb": 10, "A#": 10, "B": 11
    }

    def get_pitch(note: str) -> int:
        # Simplifica o mapeamento para lidar com variações de nomes (ex: Db vs C#)
        normalized_note = note.upper().replace(" ", "")
        if normalized_note in NOTE_VALUES:
            return NOTE_VALUES[normalized_note]
        # Fallback simples, assumindo que a entrada é válida
        return 0

    pitches = []
    for nota in notas:
        pitch = get_pitch(nota)
        pitches.append((pitch, nota))

    # Ordena os pitches para identificar o mais grave (o baixo)
    pitches.sort(key=lambda x: x[0])
    
    p1, p2, p3 = pitches[0][0], pitches[1][0], pitches[2][0]
    
    # O pitch mais grave é o que está no baixo
    pitch_baixo = p1

    # Determina a função de cada nota em relação ao root (R)
    # Assumimos que os intervalos são baseados na distância semitom.
    
    # 1. Encontra o Root (R) e calcula as distâncias para determinar se o baixo é R, 3rd ou 5th.
    
    # Tentativa de identificar a função do pitch_baixo:
    
    # Se p1 for o root, os intervalos devem ser {0, 4, 7} (Maior) ou {0, 3, 7} (Menor).
    # O intervalo entre as notas mais graves e as outras duas define a função.

    # Calculamos os intervalos absolutos:
    intervalo_12 = abs(p2 - p1)
    intervalo_13 = abs(p3 - p1)
    intervalo_23 = abs(p3 - p2)

    # Se o baixo (p1) for a Root, os intervalos devem ser R->3rd e R->5th.
    # Exemplo: C-E-G -> 0, 4, 7. Intervalos de p1: {4, 7}
    if abs(intervalo_23 - intervalo_12) == 4 and max(intervalo_12, intervalo_13) == 7: # Exemplo Major (p1=R)
        return 'estado fundamental'

    # Se o baixo (p1) for a Third, ele deve ser um terceiro acima de algum root R.
    # O Root seria p2 - 4 ou p3 - 4 (dependendo da tonalidade).
    # Neste caso, os intervalos entre as notas são {3rd-R, 5th-R}. E o baixo é 3rd.
    # Se assumirmos que a tríade é Major: R -> 3rd (p1) -> 5th (p2 ou p3).
    if abs(intervalo_23 - intervalo_12) == 3 and max(intervalo_12, intervalo_13) == 7: # Exemplo Minor (p1=3rd)
        return 'primeira inversao'

    # Se o baixo (p1) for a Fifth.
    if abs(intervalo_23 - intervalo_12) == 4 and max(intervalo_12, intervalo_13) == 7: # Exemplo Major (p1=5th)
        return 'segunda inversao'

    # Fallback genérico baseado na ordem de pitch e a suposição de que o acorde é válido.
    # Se p1 for o mais grave, ele representa o papel do baixo.
    if abs(p2 - p3) == 4 and abs(p1 - p2) == 7: # Exemplo (R-5th-3rd) -> Impossível de determinar sem root
        pass

    # Devido à ambiguidade da identificação do Root apenas com os pitches,
    # a solução deve se basear na suposição mais simples e direta:
    # 1. Se o baixo é o pitch mais grave (p1).
    # 2. O papel de p1 será determinado pela sua relação com o intervalo mínimo esperado para um acorde.

    # Reavaliando a lógica: A única forma robusta sem saber o root é assumir que o Root está no baixo,
    # ou que os intervalos definem claramente qual nota deve ser o Root.

    # Se p1 for R (estado fundamental): O intervalo entre as notas mais graves e as outras duas define {3rd, 5th}.
    if abs(p2 - p1) > 0 and abs(p3 - p1) > 0: # Verifica se há diferença de pitch
        # Se o baixo for R, os intervalos (R->3rd) e (R->5th) devem ser maiores que zero.
        return 'estado fundamental'

    # Caso contrário, assumimos a ordem crescente dos pitches como indicador do papel.
    if abs(p2 - p1) > 0 and abs(p3 - p2) == 4: # Exemplo de primeira inversão (Root está em p2 ou p3)
        return 'primeira inversao'

    # Caso padrão para segunda inversão (o baixo é o quinto).
    return 'segunda inversao'<unused56>
def detectar_inversao(notas: list[str]) -> str:
    # Função auxiliar para converter notas em um valor numérico comparável (simulando MIDI ou pitch class)
    # Nota: Em um ambiente real, seria necessário um parser de notas robusto que inclua octavas.
    # Aqui, assumimos que as notas são fornecidas em uma ordem/formato que permite a comparação correta do pitch mais baixo.
    def get_pitch_value(note: str) -> int:
        # Mapeamento simplificado para garantir que o 'min' funcione corretamente
        # Exemplo: C4=60, D4=62, E4=64, G4=67
        if note.upper() == "C": return 1
        if note.upper() == "D": return 2
        if note.upper() == "E": return 3
        if note.upper() == "F": return 4
        if note.upper() == "G": return 5
        if note.upper() == "A": return 6
        if note.upper() == "B": return 7
        # Adicionar lógica de octava se necessário, mas para o escopo do problema, assumimos que a comparação é suficiente.
        return 0 # Fallback

    pitch_values = [get_pitch_value(n) for n in notas]

    # Encontra o índice da nota mais grave (o baixo)
    min_pitch = min(pitch_values)
    bass_index = pitch_values.index(min_pitch)
    nota_baixo = notas[bass_index]

    # Para determinar se a nota de baixo é R, T ou P5, precisamos identificar o Root (R), Third (T) e Fifth (P5)
    # Primeiro, ordenamos as notas para facilitar a identificação dos intervalos.
    notas_ordenadas = sorted(list(set(notas)), key=get_pitch_value)

    if len(notas_ordenadas) < 3:
        # Caso de acorde incompleto ou dissonante que não segue o padrão tríade simples R-T-P5
        return "Indefinido"

    # Assumindo que as notas ordenadas representam a estrutura R, T, P5 (ou pelo menos os graus)
    root_candidate = notas_ordenadas[0]
    third_candidate = notas_ordenadas[1]
    fifth_candidate = notas_ordenadas[2]

    # Verifica qual papel o baixo desempenha:
    if nota_baixo == root_candidate:
        return 'estado fundamental'
    elif nota_baixo == third_candidate:
        return 'primeira inversao'
    elif nota_baixo == fifth_candidate:
        return 'segunda inversao'

    # Caso de empate ou erro na lógica de identificação (deveria ser coberto pelos casos acima)
    return "Erro na detecção"
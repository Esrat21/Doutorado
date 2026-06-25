def classificar_tetrade(notas: list[str]) -> str:
    try:
        semitons = [int(n) for n in notas]
    except ValueError:
        return "Erro de entrada"

    if len(semitons) != 4:
        return "Tetrade inválida (deve ter 4 notas)"

    root_semitone = semitons[0]
    
    # Calcular os intervalos em semitons a partir da tônica (Root)
    intervalo_3 = abs(semitons[1] - root_semitone) % 12
    intervalo_5 = abs(semitons[2] - root_semitone) % 12
    intervalo_7 = abs(semitons[3] - root_semitone) % 12

    # Normalização dos intervalos para o sistema de semitons (0 a 11)
    # Ajuste para garantir que os valores sejam consistentes com as teorias musicais padrão.
    # Assumimos que os intervalos calculados são os corretos, mas precisamos checar se eles correspondem aos padrões:

    # Padrões teóricos de semitons (em relação à tônica):
    # Maj7: (3=4, 5=7, 7=11)
    # Dom7: (3=4, 5=7, 7=10)
    # m7:  (3=3, 5=7, 7=10)
    # half_diminuto: (3=3, 5=6, 7=10)

    if intervalo_3 == 4 and intervalo_5 == 7 and intervalo_7 == 11:
        return "maior7"
    elif intervalo_3 == 4 and intervalo_5 == 7 and intervalo_7 == 10:
        return "dominante7"
    elif intervalo_3 == 3 and intervalo_5 == 7 and intervalo_7 == 10:
        return "menor7"
    elif intervalo_3 == 3 and intervalo_5 == 6 and intervalo_7 == 10:
        return "meio_diminuto"
    else:
        # Se não corresponder a nenhum padrão principal, retorna um valor genérico ou erro.
        return "Não classificado"
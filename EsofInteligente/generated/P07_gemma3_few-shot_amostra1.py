def validar_progressao(graus: list[str]) -> bool:
    if not graus or graus[-1] != "I":
        return False

    prefixo = graus[:-1]
    
    if not prefixo:
        return False

    for grau in prefixo:
        if grau == "IV" or grau == "V":
            return True
            
    return False
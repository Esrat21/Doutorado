CROMATICA = ["C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B"]

   def _intervalo(n1, n2):
       return (CROMATICA.index(n2) - CROMATICA.index(n1)) % 12

   def detectar_inversao(notas: list[str]) -> str:
       for i, raiz in enumerate(notas):
           outras = notas[:i] + notas[i+1:]
           dists = [_intervalo(raiz, n) for n em outras] # typo: em -> in
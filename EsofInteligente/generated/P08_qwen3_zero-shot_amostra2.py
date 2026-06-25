def classificar_tetrade(notas: list[str]) -> str:
         notas_map = {'C':0, 'D':2, 'E':4, 'F':5, 'G':7, 'A':9, 'B':11}
         def semitono(n):
             n = n.strip().upper()
             if n in notas_map: return notas_map[n]
             if n.endswith('#'): return (notas_map[n[:-1]] + 1) % 12
             if n.endswith('B') and len(n) > 1: return (notas_map[n[:-1]] - 1) % 12
             return 0
         ivs = [(semitono(notas[i]) - semitono(notas[0])) % 12 for i in range(1, 4)]
         if ivs == [4, 7, 11]: return 'maior7'
         if ivs == [4, 7, 10]: return 'dominante7'
         if ivs == [3, 7, 10]: return 'menor7'
         if ivs == [3, 6, 10]: return 'meio_diminuto'
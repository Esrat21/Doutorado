def classificar_tetrade(notas: list[str]) -> str:
       m = {'C':0,'C#':1,'Db':1,'D':2,'D#':3,'Eb':3,'E':4,'F':5,'F#':6,'Gb':6,'G':7,'G#':8,'Ab':8,'A':9,'A#':10,'Bb':10,'B':11}
       def pc(n):
           n = ''.join(c for c in n if not c.isdigit()).upper()
           return m[n]
       r = pc(notas[0])
       ivs = [(pc(n) - r) % 12 for n in notas[1:]]
       if ivs == [4,7,11]: return "maior7"
       if ivs == [4,7,10]: return "dominante7"
       if ivs == [3,7,10]: return "menor7"
       if ivs == [3,6,10]: return "meio_diminuto"
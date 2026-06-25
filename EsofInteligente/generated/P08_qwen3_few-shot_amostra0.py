def classificar_tetrade(notas: list[str]) -> str:
       mapa = {'C':0,'C#':1,'Db':1,'D':2,'D#':3,'Eb':3,'E':4,'Fb':4,'F':5,'F#':6,'Gb':6,'G':7,'G#':8,'Ab':8,'A':9,'A#':10,'Bb':10,'B':11,'Cb':11}
       ref = mapa[notas[0].upper()]
       intervalos = [(mapa[n.upper()] - ref) % 12 for n in notas]
       if intervalos == [0, 4, 7, 11]: return "maior7"
       if intervalos == [0, 4, 7, 10]: return "dominante7"
       if intervalos == [0, 3, 7, 10]: return "menor7"
       if intervalos == [0, 3, 6, 10]: return "meio_diminuto"
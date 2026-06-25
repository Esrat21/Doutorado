def classificar_tetrade(notas: list[str]) -> str:
       m = {'C':0, 'Db':1, 'D':2, 'Eb':3, 'E':4, 'F':5, 'Gb':6, 'G':7, 'Ab':8, 'A':9, 'Bb':10, 'B':11}
       m.update({'C#':1, 'D#':3, 'F#':6, 'G#':8, 'A#':10})
       s = [m[n] for n in notas]
       i = tuple((x - s[0]) % 12 for x in s[1:])
       if i == (4, 7, 11): return "maior7"
       if i == (4, 7, 10): return "dominante7"
       if i == (3, 7, 10): return "menor7"
       if i == (3, 6, 10): return "meio_diminuto"
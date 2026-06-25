def get_semitone(n):
         base = {'C':0, 'D':2, 'E':4, 'F':5, 'G':7, 'A':9, 'B':11}
         if n[-1] == '#':
             return (base[n[:-1]] + 1) % 12
         elif n[-1] == 'b':
             return (base[n[:-1]] - 1) % 12
         else:
             return base[n]
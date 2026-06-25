NOTE_TO_SEMI = {
       'C': 0, 'Db': 1, 'D': 2, 'Eb': 3, 'E': 4, 'F': 5, 'Gb': 6, 'G': 7, 'Ab': 8, 'A': 9, 'Bb': 10, 'B': 11
   }
   # Add sharps as aliases
   for k in list(NOTE_TO_SEMI.keys()):
       if k.endswith('b'):
           NOTE_TO_SEMI[k[:-1] + '#'] = NOTE_TO_SEMI[k]
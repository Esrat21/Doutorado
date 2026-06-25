def get_pc(n):
           n = n.lower().strip()
           # remove octave
           for i, c in enumerate(n):
               if c.isdigit():
                   n = n[:i]
                   break
           mapping = {
               'c': 0, 'db': 1, 'd#': 1, 'd': 2, 'eb': 3, 'e#': 3, 'e': 4,
               'f': 5, 'gb': 6, 'f#': 6, 'g': 7, 'ab': 8, 'g#': 8, 'a': 9,
               'bb': 10, 'a#': 10, 'b': 11, 'cb': 11, 'e#': 4 # e# is actually f (5), but pc-wise it's 4? No, E# is enharmonic to F, so pc=5. I'll just stick to standard: C Db D Eb E F Gb G Ab A Bb B
           }
           # Simpler: just use a dict with exact matches for common cases.
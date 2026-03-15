import pandas as pd

df = pd.read_csv('dataset/dataset.csv', encoding='utf-8')
print(f'Total: {len(df)} registros')
print(f'\nColunas: {list(df.columns)}')
print(f'\nAnos disponíveis: {sorted(df["Year"].dropna().unique())}')
print(f'\nPrimeiras linhas:')
print(df[['Title', 'Year', 'Authors', 'Source']].head(3))


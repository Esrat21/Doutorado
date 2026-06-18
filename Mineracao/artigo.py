import pandas as pd 
import numpy as np
import matplotlib.pyplot as plt

df = pd.read_csv("./data/mxmh_survey_results.csv")

freq_cols = [col for col in df.columns if col.startswith("Frequency [")]

counts_per_genre = {}
for col in freq_cols:
    counts_per_genre[col] = df[col].value_counts()

counts_df = pd.DataFrame(counts_per_genre).fillna(0)

order = ["Never", "Rarely", "Sometimes", "Very frequently"]
counts_df = counts_df.reindex(order).fillna(0)

counts_df.T.plot(kind="bar", stacked=True, figsize=(14, 7))
plt.title("Distribuição das frequências por gênero")
plt.xlabel("Gênero")
plt.ylabel("Quantidade de respostas")
plt.xticks(rotation=75)
plt.tight_layout()
plt.show()
# 📚 Mapeamento Sistemático da Literatura — PRISMA 2020

## Inteligência Artificial no Ensino e na Análise de Performance de Guitarra Elétrica e Violão

## 1. Visão Geral do Projeto

Este repositório contém o workflow computacional utilizado para conduzir um Mapeamento Sistemático da Literatura, seguindo as diretrizes PRISMA 2020, sobre a aplicação de técnicas de Inteligência Artificial no ensino e na análise de performance de guitarra elétrica e violão.

O projeto tem como objetivo identificar, organizar e analisar estudos que investigam o uso de Inteligência Artificial, Aprendizado de Máquina, Aprendizado Profundo e técnicas relacionadas em sistemas voltados para:

- aprendizagem de guitarra elétrica;
- aprendizagem de violão;
- análise automática de performance instrumental;
- feedback automático ao estudante;
- avaliação de execução musical;
- sistemas tutores inteligentes;
- aprendizagem adaptativa em educação musical;
- uso de dados musicais, áudio, tablaturas, partituras ou sinais de performance para apoio ao ensino.

O workflow foi estruturado em Jupyter Notebook e scripts Python, com geração de arquivos CSV intermediários e finais, permitindo rastreabilidade, transparência metodológica e reprodutibilidade do processo de revisão.

---

## 2. Tipo de Estudo

Este projeto corresponde a um Mapeamento Sistemático da Literatura.

Diferentemente de uma revisão narrativa, o mapeamento sistemático busca identificar, classificar e sintetizar a produção científica existente em uma área, permitindo reconhecer tendências, técnicas utilizadas, lacunas de pesquisa e oportunidades futuras.

A condução do estudo segue a estrutura geral do PRISMA 2020, contemplando as seguintes etapas:

- identificação dos estudos;
- remoção de duplicatas;
- triagem por título e resumo;
- elegibilidade por leitura de texto completo;
- inclusão dos estudos finais;
- extração dos dados;
- síntese dos resultados.

---

## 3. Objetivo do Mapeamento

O objetivo principal deste estudo é mapear como técnicas de Inteligência Artificial vêm sendo utilizadas em sistemas de aprendizagem, análise e avaliação de performance de guitarra elétrica e violão.

De forma específica, o estudo busca identificar:

- quais tipos de sistemas baseados em IA foram propostos;
- quais técnicas de IA, ML e DL são empregadas;
- quais dados ou sinais de entrada são utilizados;
- como a performance instrumental é analisada;
- quais formas de feedback são oferecidas aos estudantes;
- se os sistemas apresentam adaptatividade, personalização ou analytics;
- quais lacunas técnicas e pedagógicas permanecem abertas.

---

## 4. Questões de Pesquisa

O mapeamento é orientado pelas seguintes questões de pesquisa:

### RQ1

Quais tipos de sistemas de aprendizagem de guitarra/violão baseados em Inteligência Artificial têm sido propostos?

### RQ2

Quais técnicas de Inteligência Artificial, Aprendizado de Máquina e Aprendizado Profundo são empregadas nesses sistemas?

### RQ3

Que tipos de dados e sinais de entrada esses sistemas utilizam para analisar a performance instrumental?

### RQ4

De que forma os sistemas fornecem feedback e avaliam a performance dos estudantes?

### RQ5

Como os sistemas incorporam aspectos pedagógicos, como adaptatividade, personalização de trajetórias de estudo, progressão de dificuldades e visualizações/analytics do desempenho?

### RQ6

Quais lacunas técnicas e pedagógicas permanecem na literatura para o desenvolvimento de ambientes de aprendizagem de guitarra/violão verdadeiramente dinâmicos e adaptativos?

---

## 5. Estrutura do Repositório

A estrutura sugerida para o projeto é a seguinte:

```text
project-root/
│
├── README.md
├── main.ipynb
├── requirements.txt
│
├── dataset/
│   ├── raw/
│   │   ├── scopus_raw.csv
│   │   ├── google_scholar_raw.csv
│   │   └── semantic_scholar_raw.csv
│   │
│   ├── processed/
│   │   ├── literature_merged.csv
│   │   ├── literature_dedup.csv
│   │   ├── title_abstract_screening_template.csv
│   │   ├── studies_included_after_screening.csv
│   │   ├── studies_excluded_after_screening.csv
│   │   ├── full_text_screening_template.csv
│   │   ├── studies_included_final.csv
│   │   └── studies_excluded_full_text.csv
│   │
│   └── extraction/
│       ├── data_extraction_template.csv
│       ├── data_extraction_completed.csv
│       ├── quality_assessment_template.csv
│       └── quality_assessment_completed.csv
│
├── prisma/
│   ├── prisma_counts.json
│   ├── prisma_diagram.png
│   └── prisma_checklist.md
│
└── outputs/
    ├── tables/
    ├── figures/
    └── summaries/
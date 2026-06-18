# Conclusões do Mapeamento Sistemático

## 1. Síntese Geral

As conclusões abaixo são preliminares e foram geradas com base em 653 registros provenientes da etapa de **estudos incluídos após triagem por título e resumo**. A análise sugere que a literatura sobre IA aplicada à aprendizagem e análise de performance de guitarra/violão se organiza em torno de sistemas de transcrição, reconhecimento, avaliação, feedback, personalização e apoio à prática instrumental.

## 2. Principais Achados


- Foram identificados indícios de diferentes tipos de sistemas, com destaque para:
- **dataset/recurso para modelagem:** 253 ocorrência(s) relativas (40.0% entre as categorias detectadas).
- **sistema adaptativo/personalizado:** 159 ocorrência(s) relativas (25.2% entre as categorias detectadas).
- **sistema de aprendizagem:** 123 ocorrência(s) relativas (19.5% entre as categorias detectadas).
- **sistema de avaliação:** 38 ocorrência(s) relativas (6.0% entre as categorias detectadas).
- **sistema de feedback:** 30 ocorrência(s) relativas (4.7% entre as categorias detectadas).
- **sistema de transcrição/tablatura:** 29 ocorrência(s) relativas (4.6% entre as categorias detectadas).

- As técnicas de IA mais recorrentes foram:
- **Machine Learning:** 388 ocorrência(s) relativas (27.9% entre as categorias detectadas).
- **Deep Learning:** 308 ocorrência(s) relativas (22.2% entre as categorias detectadas).
- **Transformers/Attention:** 147 ocorrência(s) relativas (10.6% entre as categorias detectadas).
- **Artificial Intelligence:** 145 ocorrência(s) relativas (10.4% entre as categorias detectadas).
- **CNN:** 118 ocorrência(s) relativas (8.5% entre as categorias detectadas).
- **Generative AI:** 89 ocorrência(s) relativas (6.4% entre as categorias detectadas).
- **RNN/LSTM/GRU:** 76 ocorrência(s) relativas (5.5% entre as categorias detectadas).
- **Computer Vision/Pose Estimation:** 54 ocorrência(s) relativas (3.9% entre as categorias detectadas).
- **Recommendation Systems:** 35 ocorrência(s) relativas (2.5% entre as categorias detectadas).
- **Reinforcement Learning:** 29 ocorrência(s) relativas (2.1% entre as categorias detectadas).

- Os dados de entrada mais citados foram:
- **vídeo/imagem:** 450 ocorrência(s) relativas (45.9% entre as categorias detectadas).
- **áudio:** 226 ocorrência(s) relativas (23.1% entre as categorias detectadas).
- **MIDI/simbólico:** 124 ocorrência(s) relativas (12.7% entre as categorias detectadas).
- **gestos/movimento:** 95 ocorrência(s) relativas (9.7% entre as categorias detectadas).
- **dados multimodais:** 51 ocorrência(s) relativas (5.2% entre as categorias detectadas).
- **tablatura:** 24 ocorrência(s) relativas (2.4% entre as categorias detectadas).
- **dados de aprendizagem:** 10 ocorrência(s) relativas (1.0% entre as categorias detectadas).

- As formas de feedback e avaliação mais presentes foram:
- **avaliação automática:** 225 ocorrência(s) relativas (32.8% entre as categorias detectadas).
- **análise de técnica:** 195 ocorrência(s) relativas (28.5% entre as categorias detectadas).
- **análise de pitch/ritmo:** 146 ocorrência(s) relativas (21.3% entre as categorias detectadas).
- **feedback visual:** 79 ocorrência(s) relativas (11.5% entre as categorias detectadas).
- **acompanhamento de progresso:** 15 ocorrência(s) relativas (2.2% entre as categorias detectadas).
- **feedback em tempo real:** 13 ocorrência(s) relativas (1.9% entre as categorias detectadas).
- **feedback personalizado:** 12 ocorrência(s) relativas (1.8% entre as categorias detectadas).

- Os aspectos pedagógicos mais mencionados foram:
- **adaptatividade:** 86 ocorrência(s) relativas (37.7% entre as categorias detectadas).
- **personalização:** 66 ocorrência(s) relativas (28.9% entre as categorias detectadas).
- **analytics/dashboards:** 30 ocorrência(s) relativas (13.2% entre as categorias detectadas).
- **trajetória de aprendizagem:** 29 ocorrência(s) relativas (12.7% entre as categorias detectadas).
- **sistema tutor inteligente:** 12 ocorrência(s) relativas (5.3% entre as categorias detectadas).
- **autorregulação:** 5 ocorrência(s) relativas (2.2% entre as categorias detectadas).

- As lacunas preliminares mais prováveis foram:
- **lacunas de avaliação empírica:** 378 ocorrência(s) relativas (71.2% entre as categorias detectadas).
- **generalização limitada:** 77 ocorrência(s) relativas (14.5% entre as categorias detectadas).
- **baixa integração pedagógica:** 41 ocorrência(s) relativas (7.7% entre as categorias detectadas).
- **falta de feedback em tempo real:** 26 ocorrência(s) relativas (4.9% entre as categorias detectadas).
- **limitação de datasets:** 9 ocorrência(s) relativas (1.7% entre as categorias detectadas).


## 3. Implicações para o Desenvolvimento de Sistemas de Aprendizagem de Guitarra/Violão

Os resultados indicam que um sistema de aprendizagem de guitarra/violão orientado por IA deve combinar análise automática de áudio, suporte a tablatura, reconhecimento de técnica, feedback em tempo real, visualizações compreensíveis para o estudante e mecanismos de personalização. A literatura também sugere que a contribuição pedagógica não deve se limitar à classificação automática de erros, mas avançar para recomendações de estudo, progressão de dificuldade, explicações interpretáveis e acompanhamento longitudinal do desempenho.

## 4. Limitações do Mapeamento

As principais limitações identificadas até esta etapa são:

- dependência das bases consultadas e dos metadados exportados;
- ausência ou incompletude de resumos em alguns registros;
- possível presença de falsos positivos decorrentes de termos ambíguos;
- disponibilidade limitada de texto completo para todos os estudos;
- heterogeneidade dos estudos em relação a métodos, instrumentos, bases de dados e formas de avaliação;
- a síntese automática depende de palavras-chave presentes nos títulos e resumos;
- estudos relevantes podem ter sido classificados de forma incompleta quando o resumo não explicita técnica, instrumento ou aplicação pedagógica;
- as categorias precisam ser confirmadas por leitura completa e extração manual;
- os estudos marcados como `talvez` exigem revisão humana para reduzir falsos positivos e falsos negativos.

## 5. Trabalhos Futuros

Com base na síntese preliminar, os trabalhos futuros podem seguir as seguintes direções:

- concluir a recuperação e leitura dos textos completos;
- preencher o arquivo `dataset/extraction/data_extraction_completed.csv`;
- validar manualmente os estudos classificados como `talvez`;
- refinar as categorias de sistemas, técnicas de IA, dados de entrada e feedback;
- desenvolver ambientes adaptativos para aprendizagem de guitarra/violão;
- integrar feedback em tempo real sobre pitch, ritmo, postura, técnica e execução;
- utilizar análise automática de áudio, vídeo, tablatura e sinais multimodais;
- construir dashboards de aprendizagem e acompanhamento de progresso;
- avaliar empiricamente os sistemas com estudantes reais;
- criar ou consolidar datasets públicos voltados à performance de guitarra elétrica e violão.

## 6. Consideração Final

A Inteligência Artificial aparece como mediadora potencial no ensino instrumental, sobretudo quando combinada a análise automática de performance, feedback inteligível, personalização e acompanhamento longitudinal. No entanto, a consolidação dessa contribuição depende de maior integração entre robustez técnica, fundamentação pedagógica e validação empírica com aprendizes de guitarra elétrica e violão.

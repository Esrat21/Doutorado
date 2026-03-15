# 📚 Revisão Sistemática de Literatura (PRISMA 2020)
## Inteligência Artificial para Aprendizado e Análise de Performance de Guitarra Elétrica

## 1. Visão Geral do Projeto

Este repositório contém o workflow completo para conduzir uma Revisão Sistemática de Literatura (RSL) seguindo as diretrizes PRISMA 2020, com foco na aplicação de Inteligência Artificial (IA) para:

- aprendizado de guitarra elétrica,
- análise de performance de guitarra,
- sistemas de feedback automático,
- avaliação expressiva e técnica em educação musical.

O workflow é implementado usando Jupyter Notebook, scripts Python e artefatos CSV estruturados para garantir transparência, reprodutibilidade e rigor metodológico.

## 2. Framework Metodológico

Esta revisão segue estritamente a metodologia PRISMA 2020, cobrindo os seguintes estágios:

- **Identificação**
- **Triagem**
- **Elegibilidade**
- **Inclusão**

Cada estágio é documentado através de artefatos de código e documentação explícita (blocos Markdown), conforme exigido pelo PRISMA.

## 3. Estrutura de Pastas

O projeto segue a estrutura abaixo:

```
project-root/
│
├── README.md
├── main.ipynb
│
├── dataset/
│   ├── scholar_raw.csv
│   ├── ieee_raw.csv
│   ├── springer_raw.csv
│   ├── crossref_raw.csv
│   ├── arxiv_raw.csv
│   ├── semantic_scholar_raw.csv
│   │
│   ├── literature_dedup.csv
│   ├── title_abstract_screening_template.csv
│   ├── studies_included_after_screening.csv
│   ├── studies_excluded_after_screening.csv
│   ├── data_extraction_template.csv
│   ├── quality_assessment_template.csv
│
├── prisma/
│   ├── prisma_diagram.png
│   ├── prisma_counts.json
│   └── prisma_checklist.md
│
└── requirements.txt
```

## 4. Fontes de Dados (Estágio de Identificação)

As seguintes bases de dados devem ser consultadas:

### 4.1 Google Scholar
- **Ferramenta**: Publish or Perish
- **Formato de exportação**: CSV
- **Queries executadas manualmente e exportadas**
- **Arquivo**: `scholar_raw.csv`

### 4.2 IEEE Xplore
- **Método de acesso**: IEEE Xplore API
- **Requer**: Chave de API válida e ATIVA
- **Arquivo**: `ieee_raw.csv`

### 4.3 SpringerLink
- **Método de acesso**: Springer Nature API
- **Arquivo**: `springer_raw.csv`

### 4.4 Crossref
- **Método de acesso**: Crossref API
- **Arquivo**: `crossref_raw.csv`

### 4.5 arXiv
- **Método de acesso**: arXiv API
- **Arquivo**: `arxiv_raw.csv`

### 4.6 Semantic Scholar
- **Método de acesso**: Semantic Scholar API
- **Arquivo**: `semantic_scholar_raw.csv`

⚠️ **Importante**: Se uma fonte retornar zero resultados, seu CSV pode estar vazio e deve ser ignorado com segurança pelo notebook.

## 5. Documentação da Estratégia de Busca (Requisito PRISMA)

Para cada base de dados, o seguinte deve ser documentado (em Markdown dentro de `main.ipynb`):

- query de busca exata usada,
- data de execução,
- nome da base de dados,
- filtros aplicados (intervalo de anos, idioma, tipo de documento).

Exemplos de queries são documentados no notebook.

## 6. Critérios de Inclusão e Exclusão

### 6.1 Critérios de Inclusão

Os estudos devem:

- abordar IA, aprendizado de máquina ou sistemas inteligentes;
- focar em guitarra elétrica ou contextos de instrumentos de corda diretamente transferíveis;
- envolver aprendizado, análise de performance ou feedback;
- ser escritos em inglês;
- ser publicados entre 2013 e 2025.

### 6.2 Critérios de Exclusão

Os estudos são excluídos se:

- focarem em composição musical, síntese de som ou reconhecimento de fala;
- abordarem MIR sem objetivos educacionais ou de avaliação de performance;
- não envolverem qualquer método baseado em IA;
- forem duplicatas, pôsteres ou apenas resumos.

Esses critérios são explicitamente documentados no notebook.

## 7. Processo de Desduplicação (PRISMA – Triagem)

A desduplicação é realizada em três etapas:

1. Desduplicação baseada em DOI
2. Correspondência exata de título (títulos normalizados)
3. Correspondência de similaridade fuzzy de título (≥ 94%)

**Arquivo de saída**: `literature_dedup.csv`

## 8. Triagem de Título e Resumo

Uma etapa de triagem manual é obrigatória sob PRISMA.

### 8.1 Template de Triagem

O notebook gera:

- `title_abstract_screening_template.csv`

**Campos**:
- `include_title_screening` (sim/não)
- `exclusion_reason`

Este arquivo deve ser preenchido manualmente e reimportado.

### 8.2 Saídas de Triagem

- `studies_included_after_screening.csv`
- `studies_excluded_after_screening.csv`

## 9. Diagrama de Fluxo PRISMA

O notebook calcula:

- total de registros identificados,
- registros após desduplicação,
- registros após triagem,
- estudos finais incluídos.

Um diagrama de fluxo PRISMA é gerado e salvo como:

- `prisma/prisma_diagram.png`

## 10. Extração de Dados (Estágio de Elegibilidade)

Uma Planilha de Extração de Dados padronizada é gerada:

- `data_extraction_template.csv`

**Campos incluem**:
- objetivos,
- desenho do estudo,
- técnicas de IA,
- fontes de dados,
- tipo de feedback,
- dimensões de performance,
- resultados,
- limitações,
- trabalho futuro.

Este arquivo é preenchido manualmente durante a leitura do texto completo.

## 11. Avaliação de Qualidade / Risco de Viés

Um template estruturado de avaliação de qualidade é gerado:

- `quality_assessment_template.csv`

Cada estudo é pontuado (1–5) em:

- clareza metodológica,
- adequação da IA,
- qualidade do conjunto de dados,
- transparência dos resultados,
- replicabilidade.

Isso atende ao requisito PRISMA para avaliação de risco de viés.

## 12. Saídas Finais (Estágio de Inclusão)

Ao final do processo, os seguintes artefatos devem existir:

- Dataset de estudos incluídos finais
- Planilha de extração de dados completa
- Matriz de avaliação de qualidade completa
- Diagrama de fluxo PRISMA
- Checklist PRISMA

Esses artefatos suportam diretamente as seções de Métodos e Resultados do artigo.

## 13. Notas de Reprodutibilidade

- Todos os scripts são determinísticos.
- Todas as etapas manuais são explicitamente registradas via templates CSV.
- Todas as decisões (exclusões, pontuações de qualidade) são rastreáveis.
- O workflow pode ser reexecutado por outro pesquisador.

## 14. Prontidão para Publicação

Esta estrutura de repositório e workflow são compatíveis com:

- PRISMA 2020
- Estudos de Mapeamento Sistemático
- Requisitos de revistas e conferências (Springer, Elsevier, ACM, IEEE)

## 15. Próximos Passos (Fora do Notebook)

1. Escrever a seção de Métodos usando as etapas PRISMA documentadas.
2. Sintetizar resultados com base nos dados extraídos.
3. Discutir limitações e direções futuras de pesquisa.
4. Submeter o manuscrito.

## 16. Como Usar

1. **Instalar dependências**:
   ```bash
   pip install -r requirements.txt
   ```

2. **Configurar chaves de API** (se necessário):
   - Edite as células do notebook com suas chaves de API para IEEE, Springer, Crossref, etc.

3. **Executar o notebook**:
   - Abra `main.ipynb` no Jupyter
   - Execute as células sequencialmente
   - Preencha os templates CSV manualmente quando solicitado

4. **Seguir o workflow PRISMA**:
   - Identificação → Triagem → Elegibilidade → Inclusão

## 17. Licença

Este projeto é destinado a uso acadêmico e de pesquisa.

## 18. Contato

Para questões sobre este projeto, consulte a documentação no notebook ou entre em contato com o autor.


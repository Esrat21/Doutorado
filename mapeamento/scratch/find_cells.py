import nbformat

with open('main.ipynb', 'r', encoding='utf-8') as f:
    nb = nbformat.read(f, as_version=4)

for i, cell in enumerate(nb.cells):
    if cell.cell_type == 'code' and 'def load_raw_files' in cell.source:
        print(f"Index {i} has load_raw_files")
    if cell.cell_type == 'code' and 'def normalize_metadata' in cell.source:
        print(f"Index {i} has normalize_metadata")
    if cell.cell_type == 'code' and 'def calculate_prisma_counts' in cell.source:
        print(f"Index {i} has calculate_prisma_counts")
    if cell.cell_type == 'code' and 'def generate_results_md' in cell.source:
        print(f"Index {i} has generate_results_md")

import sys
import re

with open('c:/dev/ferreteria/rebuild_pricing_structured_fix.py', 'r', encoding='utf-8') as f:
    code = f.read()

# I want to specifically modify how `replace_in_file` handles edit.blade.php
# but since the script already has all strings defined, I'll just write a quick customized section for edit.blade.php:

custom_script = """
def fix_edit_blade():
    filepath = 'c:/dev/ferreteria/resources/views/admin/products/edit.blade.php'
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Locate Unidades Adicionales
    match_unidades_start = re.search(r'\{\{-- Card: Unidades Adicionales --\}\}', content)
    match_unidades_end = re.search(r'(?<=                </div>\n            </div>\n\n            \{\{-- Columna Derecha)', content)
    
    if not match_unidades_start:
        print("Could not find Unidades Adicionales in edit")
        return

    # To be safe, let's just use replace with regex for the whole block from Unidades Adicionales to the end of its div
    unidades_pattern = re.compile(r'\{\{-- Card: Unidades Adicionales --\}\}.*?</div>\n                </div>\n', re.DOTALL)
    content = unidades_pattern.sub(edit_html + "\n", content, count=1)

    # Locate and delete Precios
    precios_pattern = re.compile(r'\{\{-- Card: Precios --\}\}.*?@endif ', re.DOTALL)
    content = precios_pattern.sub('', content, count=1)

    # Remove addUnit, removeUnit, units from form x-data
    content = re.sub(
        r',\s*addUnit\s*\(\)\s*\{.*?removeUnit\s*\(index\)\s*\{.*?\}',
        '',
        content,
        flags=re.DOTALL
    )
    content = re.sub(
        r',\s*units:\s*.*?\},',
        ',',
        content,
        flags=re.DOTALL
    )
    # The above regex replacing units: {{ Js::from(...) }} might fail because of the comma at the end of barcode. Let's just do:
    content = re.sub(
        r',\s*units:\s*\{\{\s*Js::from\(.*?\)\s*\}\}',
        '',
        content,
        flags=re.DOTALL
    )

    if "Alpine.data('priceControl'" not in content:
        content = content.replace('@endsection', alpine_script + '\n@endsection')

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print("Success for edit.blade.php")

fix_edit_blade()
"""

# Append it to the file and execute
with open('c:/dev/ferreteria/rebuild_pricing_structured_fix.py', 'a', encoding='utf-8') as f:
    f.write("\n" + custom_script)


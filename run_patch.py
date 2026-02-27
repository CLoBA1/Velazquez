import sys
import re

# Import or extract the exact edit_html and alpine_script from the main file manually
with open('c:/dev/ferreteria/rebuild_pricing_structured_fix.py', 'r', encoding='utf-8') as f:
    text = f.read()

# We can execute the file in a dictionary to extract its variables safely!
namespace = {}
exec(text, namespace)

edit_html = namespace['edit_html']
alpine_script = namespace['alpine_script']

filepath = 'c:/dev/ferreteria/resources/views/admin/products/edit.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace Unidades Adicionales
unidades_pattern = re.compile(r'\{\{-- Card: Unidades Adicionales --\}\}.*?</div>\n                </div>\n', re.DOTALL)
content = unidades_pattern.sub(edit_html + "\n", content, count=1)

# Delete Precios
precios_pattern = re.compile(r'\{\{-- Card: Precios --\}\}.*?@endif ', re.DOTALL)
content = precios_pattern.sub('', content, count=1)

# Clean x-data in form
content = re.sub(
    r',\s*addUnit\s*\(\)\s*\{.*?removeUnit\s*\(index\)\s*\{.*?\}',
    '',
    content,
    flags=re.DOTALL
)
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

print('Success handling edit.blade.php')

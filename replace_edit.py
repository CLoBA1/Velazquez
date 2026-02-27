import json

with open('resources/views/admin/products/create.blade.php', 'r', encoding='utf-8') as f:
    create_lines = f.readlines()

with open('resources/views/admin/products/edit.blade.php', 'r', encoding='utf-8') as f:
    edit_lines = f.readlines()

# Extract from create (Lines 213 to 438, which is index 212 to 438)
new_pricing_block = create_lines[212:438]

start_idx = -1
for i, line in enumerate(edit_lines):
    if '{{-- Card: Gestión de Precios y Presentaciones --}}' in line or '{{-- Card: Unidades Adicionales --}}' in line:
        start_idx = i
        break

end_idx = -1
for i in range(start_idx, len(edit_lines)):
    if 'Card: Imagen' in edit_lines[i] or 'Card: Fotografía' in edit_lines[i]:
        end_idx = i
        break

if start_idx != -1 and end_idx != -1:
    updated_block = []
    for line in new_pricing_block:
        line = line.replace("cost: '{{ old('cost_price', 0) }}'", "cost: '{{ old('cost_price', $product->cost_price) }}'")
        line = line.replace("tax_percent: '{{ old('taxes_percent', 0) }}'", "tax_percent: '{{ old('taxes_percent', $product->taxes_percent) }}'")
        line = line.replace("public_price: '{{ old('public_price', '') }}'", "public_price: '{{ old('public_price', $product->public_price) }}'")
        updated_block.append(line)
        
    final_lines = edit_lines[:start_idx] + updated_block + edit_lines[end_idx:]
    with open('resources/views/admin/products/edit.blade.php', 'w', encoding='utf-8') as f:
        f.writelines(final_lines)
    print('Successfully applied pricing block to edit.blade.php')
else:
    print(f'Failed to find boundaries. start={start_idx}, end={end_idx}')

import re
import os

files = [
    r'c:\dev\ferreteria\resources\views\admin\products\create.blade.php',
    r'c:\dev\ferreteria\resources\views\admin\products\edit.blade.php'
]

script_content = """        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('priceControl', (initialData) => ({
                cost: initialData.cost,
                tax_percent: initialData.tax_percent,
                net_cost: 0,

                // Base Unit Prices
                base_public_price: initialData.base_public_price,
                base_margin: '',

                // Additional Units
                units: initialData.units || [],

                addUnit() {
                    this.units.push({
                        unit_id: '',
                        cost_price: 0,
                        taxes_percent: 0,
                        calculated_cost: 0,
                        public_price: '',
                        margin: ''
                    });
                },

                removeUnit(index) {
                    this.units.splice(index, 1);
                },

                // Base Unit Calculation Logic
                updateGrossCost() {
                    let c = parseFloat(this.cost) || 0;
                    let t = parseFloat(this.tax_percent) || 0;
                    this.net_cost = c * (1 + (t / 100));
                    this.updateBaseMargin();
                },

                updateBaseMargin() {
                    let p = parseFloat(this.base_public_price);
                    if (this.net_cost > 0 && !isNaN(p)) {
                        this.base_margin = (((p / this.net_cost) - 1) * 100).toFixed(2);
                    } else {
                        this.base_margin = '';
                    }
                },

                updateBasePrice() {
                    let m = parseFloat(this.base_margin);
                    if (this.net_cost > 0 && !isNaN(m)) {
                        this.base_public_price = (this.net_cost * (1 + (m / 100))).toFixed(2);
                    }
                },

                // Extra Units Calculation Logic
                updateUnitCost(unit) {
                    let c = parseFloat(unit.cost_price) || 0;
                    let t = parseFloat(unit.taxes_percent) || 0;
                    unit.calculated_cost = (c * (1 + (t / 100))).toFixed(2);
                    this.updateUnitMargin(unit);
                },

                updateUnitMargin(unit) {
                     let cost = parseFloat(unit.calculated_cost) || 0;
                     let price = parseFloat(unit.public_price) || 0;
                     if(cost > 0 && price > 0) {
                         unit.margin = (((price / cost) - 1) * 100).toFixed(2);
                     } else {
                         unit.margin = '';
                     }
                },

                updateUnitPrice(unit) {
                    let cost = parseFloat(unit.calculated_cost) || 0;
                    let margin = parseFloat(unit.margin) || 0;
                    if(cost > 0 && !isNaN(margin)) {
                        unit.public_price = (cost * (1 + (margin / 100))).toFixed(2);
                    }
                },

                init() {
                    this.updateGrossCost(); 
                    this.$watch('cost', () => this.updateGrossCost());
                    this.$watch('tax_percent', () => this.updateGrossCost());
                    this.$watch('base_public_price', () => this.updateBaseMargin());
                    
                    // Make sure DB-loaded units have calculated nets
                    this.units.forEach(u => this.updateUnitCost(u));
                }
            }));
        });
    </script>"""

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Strip the outer addUnit logic from the form definition
    if "create.blade.php" in file:
        content = re.sub(
            r'x-data="\{ \n                        business_line.*?removeUnit\(index\).*?\n                    \}"',
            r'''x-data="{ \n                        business_line: '{{ old('business_line', 'hardware') }}',\n                        barcode: '{{ old('barcode') }}'\n                    }"''',
            content, count=1, flags=re.DOTALL
        )
        x_data_replacement = r"""x-data="priceControl({\n                                cost: {{ Js::from(old('cost_price', 0)) }},\n                                tax_percent: {{ Js::from(old('taxes_percent', 0)) }},\n                                base_public_price: {{ Js::from(old('public_price', '')) }},\n                                units: {{ Js::from(old('units', [])) }}\n                            })">"""
    else:
        content = re.sub(
            r'x-data="\{\s+business_line.*?removeUnit\(index\).*?\n                        \}"',
            r'''x-data="{ \n                            business_line: '{{ old('business_line', $product->business_line) }}',\n                            barcode: '{{ old('barcode', $product->barcode) }}'\n                        }"''',
            content, count=1, flags=re.DOTALL
        )
        x_data_replacement = r"""x-data="priceControl({\n                                cost: {{ Js::from(old('cost_price', $product->cost_price)) }},\n                                tax_percent: {{ Js::from(old('taxes_percent', $product->taxes_percent)) }},\n                                base_public_price: {{ Js::from(old('public_price', $product->public_price)) }},\n                                units: {{ Js::from(old('units', $product->units->toArray())) }}\n                            })">"""

    # 2. Extract price array logic. We use regex to match the inner x-data
    content = re.sub(
        r'x-data="\{\s*cost:.*?init\(\).*?\n.*?\}\s*\}">',
        x_data_replacement,
        content,
        flags=re.DOTALL
    )

    # 3. Add script block
    if "Alpine.data('priceControl'" not in content:
        content = content.replace("        })();\n    </script>", script_content)

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated {file}")

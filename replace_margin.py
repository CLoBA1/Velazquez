import sys

with open('resources/views/admin/products/create.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Remove base_margin: '',
content = content.replace("public_price: '{{ old('public_price', '') }}',\n                                    base_margin: '',", "public_price: '{{ old('public_price', '') }}',")

# Replace updateGrossCost
old_gross = """                                    updateGrossCost() {
                                        let c = parseFloat(this.cost) || 0;
                                        let t = parseFloat(this.tax_percent) || 0;
                                        this.net_cost = c * (1 + (t / 100));
                                        this.updateBaseMargin();
                                        this.units.forEach(u => this.updateUnitCost(u));
                                    },"""
new_gross = """                                    updateGrossCost() {
                                        let c = parseFloat(this.cost) || 0;
                                        let t = parseFloat(this.tax_percent) || 0;
                                        this.net_cost = c * (1 + (t / 100));
                                        this.units.forEach(u => this.updateUnitCost(u));
                                    },"""
content = content.replace(old_gross, new_gross)

old_unit_cost = """                                    updateUnitCost(unit) {
                                        let factor = parseFloat(unit.conversion_factor) || 0;
                                        unit.calculated_cost = (this.net_cost * factor).toFixed(2);
                                        this.updateUnitMargin(unit);
                                    },"""
new_unit_cost = """                                    updateUnitCost(unit) {
                                        let factor = parseFloat(unit.conversion_factor) || 0;
                                        unit.calculated_cost = (this.net_cost * factor).toFixed(2);
                                    },"""
content = content.replace(old_unit_cost, new_unit_cost)

# Remove the margin functions
margin_funcs = """
                                    updateBaseMargin() {
                                        let p = parseFloat(this.public_price);
                                        if (this.net_cost > 0 && !isNaN(p)) {
                                            this.base_margin = (((p / this.net_cost) - 1) * 100).toFixed(2);
                                        } else {
                                            this.base_margin = '';
                                        }
                                    },

                                    updateBasePrice() {
                                        let m = parseFloat(this.base_margin);
                                        if (this.net_cost > 0 && !isNaN(m)) {
                                            this.public_price = (this.net_cost * (1 + (m / 100))).toFixed(2);
                                        }
                                    },"""
content = content.replace(margin_funcs, "")

unit_margin_funcs = """
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
                                    },"""
content = content.replace(unit_margin_funcs, "")

with open('resources/views/admin/products/create.blade.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Done!')

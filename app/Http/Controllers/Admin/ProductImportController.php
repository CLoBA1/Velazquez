<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    public function create()
    {
        return view('admin.products.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200'], // 50MB
        ]);

        $file = $request->file('file');

        $sheets = Excel::toArray([], $file);
        $rows = $sheets[0] ?? [];

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'El archivo no tiene filas para importar.']);
        }

        $headers = array_map(fn($h) => $this->norm((string) $h), $rows[0]);
        $idx = $this->buildIndexMap($headers);

        // Columnas MÍNIMAS requeridas (más flexible)
        // Ya no exigimos family_code ni unit_symbol obligatoriamente si tenemos los nombres
        $required = [
            'name', // description opcional (se puede dejar vacía)
            'family', // basta con el nombre
            'category',
            'unit_name', // basta con el nombre
            'cost_price',
            'sale_price',
            'public_price',
        ];

        foreach ($required as $col) {
            if (!isset($idx[$col])) {
                return back()->withErrors(['file' => "Falta columna requerida: {$col} (o un alias válido)."]);
            }
        }

        $created = 0;
        $skipped = 0;
        $errors = [];

        // caches
        $familyByCode = [];
        $brandByName = [];
        $unitByKey = [];
        $categoryByKey = [];

        DB::beginTransaction();
        try {
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                if ($this->isEmptyRow($row)) {
                    continue;
                }

                try {
                    $p = $this->rowToPayload($row, $idx);

                    // Validaciones básicas
                    if ($p['name'] === '')
                        throw new \RuntimeException("El nombre del producto está vacío.");

                    // Si description viene vacía, usamos el nombre
                    if ($p['description'] === '')
                        $p['description'] = $p['name'];

                    // Precios numéricos obligatorios
                    if ($p['cost_price'] === null)
                        throw new \RuntimeException("El Costo es inválido o vacío.");
                    if ($p['sale_price'] === null)
                        throw new \RuntimeException("El Precio Venta es inválido o vacío.");
                    if ($p['public_price'] === null)
                        throw new \RuntimeException("El Precio Público es inválido o vacío.");

                    // Rellenar precios opcionales si faltan
                    if ($p['mid_wholesale_price'] === null)
                        $p['mid_wholesale_price'] = $p['public_price'];
                    if ($p['wholesale_price'] === null)
                        $p['wholesale_price'] = $p['public_price'];

                    // Generar Internal Code si no viene
                    if ($p['internal_code'] === '') {
                        // Generar uno temporal o basado en secuencia
                        // Estrategia simple: PROD-{RANDOM} o secuencia DB. 
                        // Idealmente el usuario debería darlo, pero para importar rapido lo generamos.
                        $p['internal_code'] = 'INT-' . strtoupper(Str::random(6));
                    }

                    // Duplicados
                    if (Product::where('internal_code', $p['internal_code'])->exists()) {
                        $skipped++;
                        $errors[] = [
                            'row' => $i + 1,
                            'message' => "Omitido: Código '{$p['internal_code']}' ya existe.",
                        ];
                        continue;
                    }

                    // --- FAMILIA ---
                    $familyCode = $p['family_code'];
                    $familyName = $p['family'];

                    if ($familyName === '')
                        throw new \RuntimeException("El nombre de familia es obligatorio.");

                    // Si no trae código, generamos uno del nombre
                    if ($familyCode === '') {
                        $familyCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $familyName), 0, 3));
                        if (strlen($familyCode) < 3)
                            $familyCode = str_pad($familyCode, 3, 'X');
                    }

                    $famKey = $familyCode;
                    if (!isset($familyByCode[$famKey])) {
                        // Buscar por código exacto o por nombre
                        $existingFam = Family::where('code', $familyCode)->orWhere('name', $familyName)->first();

                        if ($existingFam) {
                            $familyByCode[$famKey] = $existingFam;
                        } else {
                            $familyByCode[$famKey] = Family::create([
                                'name' => $familyName,
                                'code' => $this->uniqueCode('families', 'code', $familyCode),
                                'slug' => $this->uniqueSlug('families', $familyName),
                            ]);
                        }
                    }
                    $family = $familyByCode[$famKey];


                    // --- CATEGORIA ---
                    $catName = $p['category'];
                    if ($catName === '')
                        throw new \RuntimeException("La categoría es obligatoria.");

                    $catKey = $family->id . '|' . mb_strtolower($catName);
                    if (!isset($categoryByKey[$catKey])) {
                        $categoryByKey[$catKey] = Category::firstOrCreate(
                            ['family_id' => $family->id, 'name' => $catName],
                            ['slug' => $this->uniqueSlug('categories', $catName . ' ' . $family->code)]
                        );
                    }
                    $category = $categoryByKey[$catKey];

                    // --- MARCA ---
                    $brandName = $p['brand'];
                    if ($brandName === '')
                        $brandName = 'Genérica'; // Marca por defecto

                    $brandKey = mb_strtolower($brandName);
                    if (!isset($brandByName[$brandKey])) {
                        $brandByName[$brandKey] = Brand::firstOrCreate(
                            ['name' => $brandName],
                            ['slug' => $this->uniqueSlug('brands', $brandName)]
                        );
                    }
                    $brand = $brandByName[$brandKey];

                    // --- UNIDAD ---
                    $unitName = $p['unit_name'];
                    $unitSymbol = $p['unit_symbol'];

                    if ($unitName === '')
                        throw new \RuntimeException("El nombre de la unidad es obligatorio.");
                    // Si no hay símbolo, usar las primeras 3 letras del nombre
                    if ($unitSymbol === '') {
                        $unitSymbol = strtoupper(substr($unitName, 0, 3));
                    }

                    $unitKey = mb_strtolower($unitName);
                    if (!isset($unitByKey[$unitKey])) {
                        $unitByKey[$unitKey] = Unit::firstOrCreate(
                            ['name' => $unitName],
                            [
                                'symbol' => $unitSymbol,
                                'slug' => $this->uniqueSlug('units', $unitName),
                                'allows_decimal' => (bool) $p['unit_allows_decimal'],
                            ]
                        );
                    }
                    $unit = $unitByKey[$unitKey];

                    // Crear producto
                    Product::create([
                        'internal_code' => $p['internal_code'],
                        'supplier_sku' => $p['supplier_sku'] ?: null,
                        'barcode' => $p['barcode'] ?: null,
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                        'unit_id' => $unit->id,
                        'name' => $p['name'],
                        'description' => $p['description'],
                        'slug' => $this->uniqueSlug('products', $p['name'] . ' ' . $p['internal_code']),
                        'cost_price' => $p['cost_price'],
                        'sale_price' => $p['sale_price'],
                        'public_price' => $p['public_price'],
                        'mid_wholesale_price' => $p['mid_wholesale_price'],
                        'wholesale_price' => $p['wholesale_price'],
                        'stock' => 0, // Stock inicial 0
                        'min_stock' => 5, // Default
                    ]);

                    $created++;
                } catch (\Throwable $e) {
                    $errors[] = [
                        'row' => $i + 1,
                        'message' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['file' => 'Error crítico: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('admin.products.import.create')
            ->with('ok', "Proceso finalizado. Productos creados: {$created}. Filas omitidas/con error: " . count($errors))
            ->with('import_errors', $errors);
    }

    public function downloadTemplate()
    {
        $headers = [
            'codigo_interno',
            'nombre',
            'descripcion',
            'familia',
            'categoria',
            'marca',
            'unidad',
            'costo',
            'precio_publico',
            'precio_venta'
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);

            // Ejemplo 1
            fputcsv($file, [
                'PROD-001',
                'Taladro Percutor 1/2',
                'Taladro profesional 500W',
                'Herramientas Eléctricas',
                'Taladros',
                'Dewalt',
                'Pieza',
                '1500.00',
                '2500.00',
                '2200.00'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=plantilla_productos.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    private function uniqueCode($table, $col, $base)
    {
        $code = $base;
        $i = 1;
        while (DB::table($table)->where($col, $code)->exists()) {
            $code = substr($base, 0, 2) . $i;
            $i++;
        }
        return $code;
    }

    private function buildIndexMap(array $headers): array
    {
        $map = [];

        $aliases = [
            'internal_code' => ['internal_code', 'codigo_interno', 'codigo', 'code', 'clave'],
            'supplier_sku' => ['supplier_sku', 'sku', 'sku_proveedor'],
            'barcode' => ['barcode', 'codigo_barras', 'cb', 'ean'],

            'name' => ['name', 'nombre', 'producto', 'titulo'],
            'description' => ['description', 'descripcion', 'detalles'],

            'family' => ['family', 'familia', 'fam'],
            'family_code' => ['family_code', 'familia_codigo', 'codigo_familia', 'familycode'],

            'category' => ['category', 'categoria', 'cat', 'subfamilia'],
            'brand' => ['brand', 'marca', 'fabricante'],

            'unit_name' => ['unit_name', 'unidad', 'unidad_nombre', 'um'],
            'unit_symbol' => ['unit_symbol', 'unidad_simbolo', 'simbolo_unidad', 'simbolo'],
            'unit_allows_decimal' => ['unit_allows_decimal', 'unidad_decimales', 'permite_decimales'],

            'cost_price' => ['cost_price', 'costo', 'precio_compra', 'costo_neto'],
            'sale_price' => ['sale_price', 'venta', 'precio_venta', 'precio_lista'],
            'public_price' => ['public_price', 'publico', 'precio_publico', 'precio_final'],
            'mid_wholesale_price' => ['mid_wholesale_price', 'medio_mayoreo', 'precio_medio_mayoreo'],
            'wholesale_price' => ['wholesale_price', 'mayoreo', 'precio_mayoreo'],
        ];

        foreach ($aliases as $key => $alts) {
            foreach ($alts as $alt) {
                // Busqueda case-insensitive un poco más robusta
                $pos = array_search($alt, $headers);
                if ($pos === false) {
                    // Try partial match if needed, but exact alias is safer.
                    // Let's stick to strict alias match for now but ensure headers are normalized
                }

                if ($pos !== false) {
                    $map[$key] = $pos;
                    break;
                }
            }
        }

        return $map;
    }

    private function rowToPayload(array $row, array $idx): array
    {
        $get = fn($k) => isset($idx[$k]) ? trim((string) ($row[$idx[$k]] ?? '')) : '';

        $toMoney = function ($v) {
            $v = trim((string) $v);
            $v = str_replace(['$', ' '], ['', ''], $v);
            // soporta "1,234.56" o "1234.56" o "1234,56"
            // Simple logic: remove all commas, ensure dot is decimal
            // If comma is used as decimal (Latin America), usually there are no other separators or dots

            // Heurística simple: 
            // Si hay comas y puntos: quitar comas (miles), punto es decimal.
            // Si solo hay comas: cambiar coma por punto (decimal).
            // Si no hay nada: tal cual.

            if (strpos($v, ',') !== false && strpos($v, '.') !== false) {
                $v = str_replace(',', '', $v); // 1,200.50 -> 1200.50
            } elseif (strpos($v, ',') !== false) {
                $v = str_replace(',', '.', $v); // 1200,50 -> 1200.50
            }

            if ($v === '')
                return null;
            if (!is_numeric($v))
                return null;
            return (float) $v;
        };

        $toBool = function ($v) {
            $v = strtolower(trim((string) $v));
            return in_array($v, ['1', 'si', 'sí', 'true', 'yes', 'y'], true);
        };

        return [
            'internal_code' => $get('internal_code'),
            'supplier_sku' => $get('supplier_sku'),
            'barcode' => $get('barcode'),

            'name' => $get('name'),
            'description' => $get('description'),

            'family' => $get('family'),
            'family_code' => strtoupper($get('family_code')),

            'category' => $get('category'),
            'brand' => $get('brand'),

            'unit_name' => $get('unit_name'),
            'unit_symbol' => $get('unit_symbol'),
            'unit_allows_decimal' => $toBool($get('unit_allows_decimal')),

            'cost_price' => $toMoney($get('cost_price')),
            'sale_price' => $toMoney($get('sale_price')),
            'public_price' => $toMoney($get('public_price')),
            'mid_wholesale_price' => $toMoney($get('mid_wholesale_price')),
            'wholesale_price' => $toMoney($get('wholesale_price')),
        ];
    }

    private function uniqueSlug(string $table, string $seed): string
    {
        $slug = Str::slug($seed);
        if ($slug === '')
            $slug = Str::random(8);

        $base = $slug;
        $i = 2;
        while (DB::table($table)->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }
        return $slug;
    }

    private function norm(string $v): string
    {
        $v = mb_strtolower(trim($v));
        $v = str_replace([' ', '-', '.'], ['_', '_', ''], $v);
        // remove accents for aliases matching
        $v = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'ü'],
            ['a', 'e', 'i', 'o', 'u', 'n', 'u'],
            $v
        );
        return $v;
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $cell) {
            if (trim((string) $cell) !== '')
                return false;
        }
        return true;
    }
}
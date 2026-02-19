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
        $fileName = $file->getClientOriginalName();

        $sheets = Excel::toArray([], $file);
        $rows = $sheets[0] ?? [];

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'El archivo no tiene filas para importar.']);
        }

        $headers = array_map(fn($h) => $this->norm((string) $h), $rows[0]);
        $idx = $this->buildIndexMap($headers);

        $required = [
            'name',
            'family',
            'category',
            'unit_name',
            'cost_price',
            'sale_price',
            'public_price',
        ];

        foreach ($required as $col) {
            if (!isset($idx[$col])) {
                return back()->withErrors(['file' => "Falta columna requerida: {$col} (o un alias válido)."]);
            }
        }

        // Init History Log
        $history = \App\Models\ImportHistory::create([
            'user_id' => auth()->id(),
            'file_name' => $fileName,
            'total_rows' => count($rows) - 1,
            'status' => 'processing', // You might want to add a status col to history later, but unrelated for now
        ]);

        $created = 0;
        $skipped = 0;
        $errorCount = 0;
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
                $rowNum = $i + 1;

                if ($this->isEmptyRow($row)) {
                    continue;
                }

                try {
                    $p = $this->rowToPayload($row, $idx);

                    // --- VALIDATIONS ---

                    // 1. Name Empty
                    if ($p['name'] === '')
                        throw new \RuntimeException("El nombre del producto está vacío.");

                    // 2. Barcode Duplicate
                    if (!empty($p['barcode']) && Product::where('barcode', $p['barcode'])->exists()) {
                        throw new \RuntimeException("Código de Barras '{$p['barcode']}' ya existe en el sistema.");
                    }

                    // 3. Internal Code Duplicate (If provided)
                    if (!empty($p['internal_code']) && Product::where('internal_code', $p['internal_code'])->exists()) {
                        throw new \RuntimeException("OMITIDO: El Código Interno '{$p['internal_code']}' ya existe.");
                    }

                    // 4. Name Duplicate (Strict Check enabled by user request)
                    // Case-insensitive check to avoid variations like "Martillo" vs "MARTILLO"
                    if (Product::whereRaw('LOWER(name) = ?', [mb_strtolower($p['name'])])->exists()) {
                        throw new \RuntimeException("OMITIDO: El producto '{$p['name']}' ya existe (por nombre).");
                    }

                    // Fill defaults
                    if ($p['description'] === '')
                        $p['description'] = $p['name'];

                    // Precios numéricos obligatorios
                    if ($p['cost_price'] === null)
                        throw new \RuntimeException("El Costo es inválido.");
                    if ($p['sale_price'] === null)
                        throw new \RuntimeException("El Precio Venta es inválido.");
                    if ($p['public_price'] === null)
                        throw new \RuntimeException("El Precio Público es inválido.");

                    // Rellenar precios
                    if ($p['mid_wholesale_price'] === null)
                        $p['mid_wholesale_price'] = $p['public_price'];
                    if ($p['wholesale_price'] === null)
                        $p['wholesale_price'] = $p['public_price'];

                    // Generar Internal Code si no viene
                    if ($p['internal_code'] === '') {
                        $p['internal_code'] = 'INT-' . strtoupper(Str::random(6));
                        // Ensure uniqueness of generated code
                        while (Product::where('internal_code', $p['internal_code'])->exists()) {
                            $p['internal_code'] = 'INT-' . strtoupper(Str::random(6));
                        }
                    }

                    // --- TAXONOMY RESOLUTION ---

                    // Family
                    $familyName = trim($p['family']);
                    if ($familyName === '')
                        throw new \RuntimeException("El nombre de familia es obligatorio.");

                    $familyCode = $p['family_code'];
                    // Generate code attempt if missing
                    if ($familyCode === '') {
                        $familyCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $familyName), 0, 3));
                        if (strlen($familyCode) < 3)
                            $familyCode = str_pad($familyCode, 3, 'X');
                    }

                    $famKey = $familyCode . '|' . mb_strtolower($familyName);
                    if (!isset($familyByCode[$famKey])) {
                        // Look up strictly case-insensitive
                        $existingFam = Family::whereRaw('LOWER(name) = ?', [mb_strtolower($familyName)])
                            ->orWhere('code', $familyCode)
                            ->first();

                        if ($existingFam) {
                            $familyByCode[$famKey] = $existingFam;
                        } else {
                            $familyByCode[$famKey] = Family::create([
                                'name' => ucfirst(mb_strtolower($familyName)), // Normalize casing
                                'code' => $this->uniqueCode('families', 'code', $familyCode),
                                'slug' => $this->uniqueSlug('families', $familyName),
                            ]);
                        }
                    }
                    $family = $familyByCode[$famKey];

                    // Category
                    $catName = trim($p['category']);
                    if ($catName === '')
                        throw new \RuntimeException("La categoría es obligatoria.");

                    $catKey = $family->id . '|' . mb_strtolower($catName);
                    if (!isset($categoryByKey[$catKey])) {
                        // Check existing in this family
                        $existingCat = Category::where('family_id', $family->id)
                            ->whereRaw('LOWER(name) = ?', [mb_strtolower($catName)])
                            ->first();

                        if ($existingCat) {
                            $categoryByKey[$catKey] = $existingCat;
                        } else {
                            $categoryByKey[$catKey] = Category::create([
                                'family_id' => $family->id,
                                'name' => ucfirst(mb_strtolower($catName)),
                                'slug' => $this->uniqueSlug('categories', $catName . ' ' . $family->code)
                            ]);
                        }
                    }
                    $category = $categoryByKey[$catKey];

                    // Brand
                    $brandName = trim($p['brand']);
                    if ($brandName === '')
                        $brandName = 'Genérica';

                    $brandKey = mb_strtolower($brandName);
                    if (!isset($brandByName[$brandKey])) {
                        $existingBrand = Brand::whereRaw('LOWER(name) = ?', [mb_strtolower($brandName)])->first();

                        if ($existingBrand) {
                            $brandByName[$brandKey] = $existingBrand;
                        } else {
                            $brandByName[$brandKey] = Brand::create([
                                'name' => ucfirst(mb_strtolower($brandName)),
                                'slug' => $this->uniqueSlug('brands', $brandName)
                            ]);
                        }
                    }
                    $brand = $brandByName[$brandKey];

                    // Unit
                    $unitName = $p['unit_name'];
                    if ($unitName === '')
                        throw new \RuntimeException("El nombre de la unidad es obligatorio.");
                    $unitSymbol = $p['unit_symbol'];
                    if ($unitSymbol === '')
                        $unitSymbol = strtoupper(substr($unitName, 0, 3));

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

                    // CREATE PRODUCT
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
                        'stock' => 0,
                        'min_stock' => 5,
                    ]);

                    $created++;

                    // Log Success
                    $history->details()->create([
                        'row_number' => $rowNum,
                        'status' => 'success',
                        'message' => 'Producto creado correctamente.',
                        'row_data' => $p
                    ]);

                } catch (\Throwable $e) {
                    $isSkip = $e instanceof \RuntimeException; // Or custom logical checks
                    // We'll treat our custom RuntimeExceptions as Skips/Warnings usually, 
                    // but for "Errors" in the summary we might want to separate them.
                    // Let's count them as errors/skips based on logic. 

                    // In this logic: "Already exists" -> Skip. "Invalid data" -> Error? 
                    // To keep it simple: everything caught here is a "Skip/Fail" for that row.

                    if (str_contains($e->getMessage(), 'ya existe')) {
                        $skipped++;
                        $status = 'skipped';
                    } else {
                        $errorCount++;
                        $status = 'error';
                    }

                    $errors[] = [
                        'row' => $rowNum,
                        'message' => $e->getMessage(),
                    ];

                    // Log Detail
                    $history->details()->create([
                        'row_number' => $rowNum,
                        'status' => $status,
                        'message' => $e->getMessage(),
                        'row_data' => isset($p) ? $p : ['raw' => $row]
                    ]);
                }
            }

            DB::commit();

            // Update History Summary
            $history->update([
                'processed_rows' => $created + $skipped + $errorCount,
                'created_count' => $created,
                'skipped_count' => $skipped,
                'error_count' => $errorCount,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            // Fatal error logging
            $history->update([
                'error_count' => $history->total_rows, // Mark all as failed effectively?
            ]);
            $history->details()->create([
                'row_number' => 0,
                'status' => 'critical_error',
                'message' => 'Error Crítico de Transacción: ' . $e->getMessage(),
            ]);

            return back()->withErrors(['file' => 'Error crítico: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('admin.products.import.create')
            ->with('ok', "Proceso finalizado. Creados: {$created}. Omitidos: {$skipped}. Errores: {$errorCount}.");
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

    public function downloadReport($id)
    {
        $history = \App\Models\ImportHistory::with(['details', 'user'])->findOrFail($id);

        // Basic security check (optional, depending on requirements)
        // if($history->user_id !== auth()->id() && !auth()->user()->isAdmin()) abort(403);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.products.import_report', compact('history'));
        return $pdf->download("reporte-importacion-{$history->id}.pdf");
    }
}
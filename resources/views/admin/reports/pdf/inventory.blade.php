<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #1a1a1a;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
            color: #1a1a1a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        .brand-col {
            background-color: #FFD700;
            color: #000;
            font-weight: bold;
        }

        .total-row {
            background-color: #e2e2e2;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Ferretería Velázquez</h1>
        <p>Reporte de Inventario General</p>
        <p>Fecha de emisión: {{ date('d/m/Y H:i') }}</p>
        @if($businessLine)
            <p><strong>Filtro:</strong> {{ ucfirst($businessLine) }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">Código</th>
                <th style="width: 30%">Producto</th>
                <th style="width: 15%">Familia / Cat.</th>
                <th style="width: 10%">Marca</th>
                <th style="width: 10%">Stock</th>
                <th style="width: 10%">Costo</th>
                <th style="width: 15%">P. Público</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->internal_code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                    <td>{{ $product->stock }} {{ $product->unit->symbol ?? '' }}</td>
                    <td>${{ number_format($product->cost_price, 2) }}</td>
                    <td>${{ number_format($product->public_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página generada automáticamente por el sistema de Ferretería Velázquez.
    </div>

    <!-- Script for page numbers if supported by dompdf configuration 
         <script type="text/php">
            if (isset($pdf)) {
                $text = "Página {PAGE_NUM} / {PAGE_COUNT}";
                $size = 8;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    -->
</body>

</html>
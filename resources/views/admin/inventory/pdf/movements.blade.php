<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte de Movimientos</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            color: #1e293b;
        }

        .header p {
            margin: 2px 0 0;
            color: #64748b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #e2e8f0;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            text-transform: uppercase;
            font-size: 9px;
        }

        .type-badge {
            padding: 2px 4px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
            display: inline-block;
        }

        .text-right {
            text-align: right;
        }

        .text-green {
            color: #166534;
        }

        .text-red {
            color: #991b1b;
        }

        /* Type colors simulated for PDF */
        .badge-purchase {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-sale {
            background: #f1f5f9;
            color: #475569;
        }

        .badge-adjustment_add {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-adjustment_sub {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-return {
            background: #f3e8ff;
            color: #6b21a8;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de Movimientos de Inventario</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Fecha</th>
                <th style="width: 25%">Producto</th>
                <th style="width: 15%">Tipo</th>
                <th style="width: 10%" class="text-right">Cant.</th>
                <th style="width: 10%" class="text-right">Stock Ant.</th>
                <th style="width: 10%" class="text-right">Stock Nuevo</th>
                <th style="width: 15%">Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $m)
                @php
                    $isPositive = in_array($m->type, ['purchase', 'adjustment_add', 'return']);
                    $typeLabels = [
                        'purchase' => 'Compra',
                        'adjustment_add' => 'Ajuste (+)',
                        'return' => 'Devolución',
                        'sale' => 'Venta',
                        'adjustment_sub' => 'Ajuste (-)',
                    ];
                @endphp
                <tr>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $m->product->name }}</strong><br>
                        <span style="color: #64748b;">{{ $m->product->internal_code }}</span>
                    </td>
                    <td>
                        <span class="type-badge badge-{{ $m->type }}">
                            {{ $typeLabels[$m->type] ?? ucfirst($m->type) }}
                        </span>
                    </td>
                    <td class="text-right {{ $isPositive ? 'text-green' : 'text-red' }}">
                        {{ $isPositive ? '+' : '-' }}{{ $m->quantity }}
                    </td>
                    <td class="text-right">{{ $m->previous_stock }}</td>
                    <td class="text-right"><strong>{{ $m->new_stock }}</strong></td>
                    <td>{{ $m->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
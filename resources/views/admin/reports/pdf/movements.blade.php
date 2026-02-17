<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Kardex de Movimientos</title>
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
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
            color: #1a1a1a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }

        .type-in {
            color: green;
            font-weight: bold;
        }

        .type-out {
            color: red;
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
    </style>
</head>

<body>
    <div class="header">
        <h1>Ferretería Velázquez</h1>
        <p>Reporte de Movimientos de Inventario</p>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
        @if($startDate || $endDate)
            <p>Periodo: {{ $startDate ?? 'Inicio' }} - {{ $endDate ?? 'Hoy' }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%">Fecha</th>
                <th style="width: 28%">Producto</th>
                <th style="width: 12%">Tipo</th>
                <th style="width: 8%">Cant.</th>
                <th style="width: 8%">Antes</th>
                <th style="width: 8%">Después</th>
                <th style="width: 12%">Usuario</th>
                <th style="width: 12%">Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $mov)
                <tr>
                    <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $mov->product->internal_code }}</strong><br>
                        {{ $mov->product->name }}
                    </td>
                    <td>
                        @if(in_array($mov->type, ['purchase', 'adjustment_add', 'return']))
                            <span
                                class="type-in">{{ $mov->type == 'purchase' ? 'Compra' : ($mov->type == 'return' ? 'Devolución' : 'Ajuste (+)') }}</span>
                        @else
                            <span class="type-out">{{ $mov->type == 'sale' ? 'Venta' : 'Ajuste (-)' }}</span>
                        @endif
                    </td>
                    <td style="text-align: center">{{ $mov->quantity }}</td>
                    <td style="text-align: center">{{ $mov->previous_stock }}</td>
                    <td style="text-align: center">{{ $mov->new_stock }}</td>
                    <td>{{ $mov->user->name ?? 'Sistema' }}</td>
                    <td>{{ $mov->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Ferretería Velázquez - Control de Inventarios
    </div>
</body>

</html>
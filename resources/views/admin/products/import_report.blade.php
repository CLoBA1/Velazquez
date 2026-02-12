<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Importación #{{ $history->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .summary {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .status-success {
            color: green;
        }

        .status-error {
            color: red;
        }

        .status-skipped {
            color: orange;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Reporte de Importación de Productos</h2>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <h3>Resumen</h3>
        <p><strong>Archivo:</strong> {{ $history->file_name }}</p>
        <p><strong>Usuario:</strong> {{ $history->user->name ?? 'Sistema' }}</p>
        <p><strong>Fecha Importación:</strong> {{ $history->created_at->format('d/m/Y H:i') }}</p>
        <hr>
        <p>
            <strong>Total Filas:</strong> {{ $history->total_rows }} |
            <strong>Creados:</strong> <span class="status-success">{{ $history->created_count }}</span> |
            <strong>Omitidos:</strong> <span class="status-skipped">{{ $history->skipped_count }}</span> |
            <strong>Errores:</strong> <span class="status-error">{{ $history->error_count }}</span>
        </p>
    </div>

    <h3>Detalle de Filas (Omitidos y Errores)</h3>
    <table>
        <thead>
            <tr>
                <th width="10%">Fila</th>
                <th width="15%">Estado</th>
                <th width="75%">Mensaje / Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history->details as $detail)
                @if($detail->status !== 'success')
                    <tr>
                        <td>{{ $detail->row_number }}</td>
                        <td>
                            @if($detail->status == 'error') <span class="status-error">Error</span>
                            @elseif($detail->status == 'skipped') <span class="status-skipped">Omitido</span>
                            @else {{ $detail->status }} @endif
                        </td>
                        <td>{{ $detail->message }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Sin observaciones. Todo se importó correctamente.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ticket #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @media print {
            @page {
                margin: 4mm;
                size: 80mm auto;
            }

            body {
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 8px 10px;
            background: #fff;
            max-width: 80mm;
        }

        /* ── No-print toolbar (visible only on screen before printing) ── */
        .no-print {
            background: #1a3a5c;
            color: white;
            padding: 8px 12px;
            text-align: center;
            margin: -8px -10px 10px -10px;
            font-family: sans-serif;
            font-size: 13px;
            cursor: pointer;
        }

        .no-print button {
            background: #f0c040;
            color: #1a3a5c;
            border: none;
            padding: 6px 18px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        .no-print a {
            color: #aac8f0;
            font-size: 11px;
            margin-left: 12px;
            text-decoration: none;
        }

        /* ── Ticket layout ── */
        .header {
            text-align: center;
            border-bottom: 1px solid #888;
            padding-bottom: 7px;
            margin-bottom: 7px;
        }

        .header img {
            width: 55px;
            margin-bottom: 3px;
        }

        .biz-name {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }

        .biz-info {
            font-size: 8px;
            color: #444;
            margin: 1px 0;
        }

        .folio-line {
            text-align: center;
            font-size: 9px;
            border-top: 1px dashed #888;
            border-bottom: 1px dashed #888;
            padding: 3px 0;
            margin: 5px 0;
        }

        .section {
            margin: 5px 0;
            font-size: 9px;
        }

        .section-title {
            font-size: 7.5px;
            text-transform: uppercase;
            color: #555;
            border-bottom: 1px solid #ccc;
            margin-bottom: 2px;
            padding-bottom: 1px;
        }

        .section p {
            margin: 1px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
            font-size: 8.5px;
        }

        th {
            font-size: 7.5px;
            text-transform: uppercase;
            border-bottom: 1px solid #888;
            border-top: 1px solid #888;
            padding: 2px 2px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 2px 2px;
            border-bottom: 1px dotted #ccc;
            vertical-align: top;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .totals {
            margin-top: 4px;
            border-top: 1px solid #888;
            font-size: 9px;
        }

        .totals-row {
            display: table;
            width: 100%;
            margin-top: 2px;
        }

        .totals-row .lbl {
            display: table-cell;
            text-align: right;
            color: #555;
            padding-right: 4px;
        }

        .totals-row .val {
            display: table-cell;
            text-align: right;
            width: 70px;
        }

        .total-final {
            display: table;
            width: 100%;
            margin-top: 4px;
            border-top: 1px solid #333;
            padding-top: 3px;
        }

        .total-final .lbl {
            display: table-cell;
            text-align: right;
            font-size: 11px;
            font-weight: bold;
            padding-right: 4px;
        }

        .total-final .val {
            display: table-cell;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            width: 70px;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            border-top: 1px dashed #888;
            padding-top: 6px;
            font-size: 7.5px;
            color: #555;
        }
    </style>
</head>
<body>

    {{-- toolbar only visible on screen --}}
    <div class="no-print">
        Ticket listo — 
        <button onclick="window.print()">🖨 Imprimir</button>
        <a href="{{ route('admin.pos') }}">← Nueva Venta</a>
    </div>

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ asset('images/logo-final.png') }}" alt="Logo">
        <p class="biz-name">Materiales para Construccion y Ferreteria Velazquez</p>
        <p class="biz-info">Av. Gral. Vicente Guerrero — Frente a la Iglesia de Sta. Maria de la Asuncion</p>
        <p class="biz-info">Tel: 736 366 2326 | WhatsApp: 733 170 3671</p>
    </div>

    {{-- FOLIO --}}
    <div class="folio-line">
        TICKET #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
        &nbsp;·&nbsp;
        {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}
    </div>

    {{-- CLIENTE --}}
    <div class="section">
        <div class="section-title">Cliente</div>
        @if($sale->client)
            <p>{{ $sale->client->name }}</p>
            @if($sale->client->rfc)<p>RFC: {{ $sale->client->rfc }}</p>@endif
            @if($sale->client->phone)<p>Tel: {{ $sale->client->phone }}</p>@endif
        @else
            <p>Publico en General</p>
        @endif
        @if($sale->user)
            <p>Atendio: {{ $sale->user->name }}</p>
        @endif
    </div>

    {{-- PRODUCTOS --}}
    <table>
        <thead>
            <tr>
                <th style="width:46%">Producto</th>
                <th class="text-center" style="width:10%">Cant</th>
                <th class="text-right" style="width:20%">P.Unit</th>
                <th class="text-right" style="width:24%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        @php
                            $name = $item->product->name ?? 'N/A';
                            $code = $item->product->internal_code ?? '';
                            $display = strlen($name) > 28
                                ? ($code ? $code . ' - ' . substr($name, 0, 18) . '...' : substr($name, 0, 28) . '...')
                                : $name;
                        @endphp
                        {{ $display }}
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTALES --}}
    @php
        $subtotal = 0; $taxAmount = 0;
        foreach ($sale->items as $item) {
            $taxRate   = $item->product ? ($item->product->taxes_percent / 100) : 0.16;
            $itemBase  = $item->total / (1 + $taxRate);
            $subtotal  += $itemBase;
            $taxAmount += $item->total - $itemBase;
        }
    @endphp

    <div class="totals">
        <div class="totals-row">
            <span class="lbl">Subtotal:</span>
            <span class="val">${{ number_format($subtotal, 2) }}</span>
        </div>
        <div class="totals-row">
            <span class="lbl">IVA:</span>
            <span class="val">${{ number_format($taxAmount, 2) }}</span>
        </div>
    </div>
    <div class="total-final">
        <span class="lbl">TOTAL:</span>
        <span class="val">${{ number_format($sale->total, 2) }}</span>
    </div>

    {{-- PAGOS --}}
    <div class="section" style="margin-top:5px;">
        <div class="section-title">Forma de Pago</div>
        @if($sale->payments->count() > 0)
            @php $totalPaid = 0; @endphp
            @foreach($sale->payments as $payment)
                @php $totalPaid += $payment->amount; @endphp
                <p>{{ ucfirst(__($payment->method)) }}: ${{ number_format($payment->amount, 2) }}</p>
            @endforeach
            @php $change = $totalPaid - $sale->total; @endphp
            @if($change > 0.01)
                <p>Cambio: ${{ number_format($change, 2) }}</p>
            @endif
        @else
            <p>{{ ucfirst(__($sale->payment_method)) }}</p>
        @endif
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <p>Gracias por su compra — Conserve su ticket</p>
    </div>

    {{-- AUTO-PRINT: fires as soon as the page loads --}}
    <script>
        window.addEventListener('load', function () {
            // Small delay to let the image (logo) finish rendering
            setTimeout(function () {
                window.print();
            }, 600);
        });
    </script>

</body>
</html>

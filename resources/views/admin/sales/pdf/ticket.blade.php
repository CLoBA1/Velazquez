<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $sale->type == 'invoice' ? 'Factura' : 'Nota de Venta' }} #{{ $sale->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        /* Layout classes */
        .ticket-layout {
            width: 100%;
        }

        .invoice-layout {
            width: 100%;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #666;
        }

        .client-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .client-info span {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            padding: 5px 2px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            font-size: 10px;
            text-transform: uppercase;
            color: #555;
        }

        td {
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            margin-top: 10px;
            border-top: 1px dashed #333;
            padding-top: 5px;
        }

        .totals .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .totals .grand-total {
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #777;
        }

        /* Specific adjustments */
        @if($sale->type == 'invoice')
            body {
                font-size: 12px;
            }

            .header h2 {
                font-size: 24px;
                text-align: left;
            }

            .header {
                text-align: left;
                display: flex;
                justify-content: space-between;
            }

        @endif
    </style>
</head>

<body class="{{ $sale->type == 'invoice' ? 'invoice-layout' : 'ticket-layout' }}">

    <div class="header">
        <h2>FERRETERÍA EXAMPLE</h2>
        <p>RFC: XAXX010101000</p>
        <p>Dirección Conocida #123, Ciudad</p>
        <p>Tel: (55) 1234-5678</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="client-info">
        <strong>Cliente:</strong>
        @if($sale->client)
            <span>{{ $sale->client->name }}</span>
            <span>{{ $sale->client->rfc }}</span>
            <span>{{ $sale->client->address }}</span>
        @else
            <span>Público General</span>
        @endif
        <br>
        <strong>Folio:</strong> #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}<br>
        <strong>Tipo:</strong> {{ ucfirst($sale->type) }}<br>
        <strong>Tipo:</strong> {{ ucfirst($sale->type) }}<br>

        @if($sale->payments->count() > 0)
            <div style="margin-top: 5px; border-top: 1px dashed #ccc; padding-top: 2px;">
                <strong>Pagos:</strong><br>
                @foreach($sale->payments as $payment)
                    - {{ ucfirst(__($payment->method)) }}: ${{ number_format($payment->amount, 2) }}<br>
                @endforeach
            </div>
        @else
            <strong>Método de Pago:</strong> {{ ucfirst(__($sale->payment_method)) }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:{{ $sale->type == 'ticket' ? '50%' : '40%' }}">Prod</th>
                <th class="text-center" style="width:15%">Cant</th>
                <th class="text-right" style="width:{{ $sale->type == 'ticket' ? '35%' : '20%' }}">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name }}
                        @if($sale->type == 'invoice')
                            <br><small style="color:#666">{{ $item->product->internal_code }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        @php
            $subtotal = 0;
            $taxAmount = 0;

            foreach ($sale->items as $item) {
                // Determine tax rate for this item (default to 16% if not found)
                $taxRate = $item->product ? ($item->product->taxes_percent / 100) : 0.16;

                // Calculate base price and tax for this item
                // Price in DB is gross (includes tax)
                $itemBase = $item->total / (1 + $taxRate);
                $itemTax = $item->total - $itemBase;

                $subtotal += $itemBase;
                $taxAmount += $itemTax;
            }
        @endphp
        <div class="row" style="text-align: right;">
            Subtotal: ${{ number_format($subtotal, 2) }}
        </div>
        <div class="row" style="text-align: right;">
            IVA: ${{ number_format($taxAmount, 2) }}
        </div>
        <div class="row grand-total" style="text-align: right;">
            Total: ${{ number_format($sale->total, 2) }}
        </div>
    </div>

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>Este documento es una representación impresa de un {{ $sale->type }}.</p>
    </div>

</body>

</html>
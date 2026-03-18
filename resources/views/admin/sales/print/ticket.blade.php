<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ticket #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* ── PRINT SETTINGS ─────────────────────────────── */
        @media print {
            @page {
                /* Standard 80mm thermal roll, auto height */
                size: 80mm auto;
                margin: 3mm 2mm;
            }
            body  { margin: 0; padding: 0; }
            .no-print { display: none !important; }
        }

        /* ── BASE ────────────────────────────────────────── */
        * { box-sizing: border-box; }

        body {
            /* Use pt for crisp scaling on thermal printers */
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            color: #000;
            margin: 0;
            padding: 6pt 7pt;
            background: #fff;
            width: 80mm;
        }

        /* ── SCREEN-ONLY TOOLBAR ─────────────────────────── */
        .no-print {
            font-family: Arial, sans-serif;
            background: #1a3a5c;
            color: #fff;
            padding: 8px 14px;
            text-align: center;
            margin: -6pt -7pt 10pt -7pt;
            font-size: 13px;
        }
        .no-print button {
            background: #f0c040;
            color: #1a3a5c;
            border: none;
            padding: 5px 16px;
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

        /* ── HEADER ──────────────────────────────────────── */
        .header {
            text-align: center;
            border-bottom: 0.5pt solid #000;
            padding-bottom: 5pt;
            margin-bottom: 5pt;
        }
        .header img {
            width: 45pt;
            margin-bottom: 2pt;
        }
        .biz-name {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2pt 0;
        }
        .biz-info {
            font-size: 7pt;
            margin: 1pt 0;
        }

        /* ── FOLIO ───────────────────────────────────────── */
        .folio-line {
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            border-top: 0.5pt dashed #000;
            border-bottom: 0.5pt dashed #000;
            padding: 3pt 0;
            margin: 4pt 0;
        }

        /* ── SECTIONS ────────────────────────────────────── */
        .section { margin: 4pt 0; }
        .section-title {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 0.5pt solid #000;
            margin-bottom: 2pt;
            padding-bottom: 1pt;
        }
        .section p { margin: 1pt 0; font-size: 8pt; }

        /* ── TABLE ───────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3pt;
        }
        th {
            font-size: 7pt;
            text-transform: uppercase;
            border-bottom: 0.5pt solid #000;
            border-top: 0.5pt solid #000;
            padding: 2pt 1pt;
            text-align: left;
            font-weight: bold;
        }
        td {
            font-size: 8pt;
            padding: 2pt 1pt;
            border-bottom: 0.3pt dotted #888;
            vertical-align: top;
        }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        /* ── TOTALS ──────────────────────────────────────── */
        .totals { margin-top: 4pt; border-top: 0.5pt solid #000; font-size: 8pt; }
        .totals-row { display: table; width: 100%; margin-top: 1pt; }
        .totals-row .lbl { display: table-cell; text-align: right; padding-right: 4pt; }
        .totals-row .val { display: table-cell; text-align: right; width: 60pt; }

        .total-final {
            display: table; width: 100%;
            margin-top: 3pt;
            border-top: 1pt solid #000;
            padding-top: 2pt;
        }
        .total-final .lbl {
            display: table-cell;
            text-align: right;
            font-size: 11pt;
            font-weight: bold;
            padding-right: 4pt;
        }
        .total-final .val {
            display: table-cell;
            text-align: right;
            font-size: 12pt;
            font-weight: bold;
            width: 60pt;
        }

        /* ── FOOTER ──────────────────────────────────────── */
        .footer {
            margin-top: 7pt;
            text-align: center;
            border-top: 0.5pt dashed #000;
            padding-top: 4pt;
            font-size: 7pt;
        }
    </style>
</head>
<body>

    {{-- Screen-only toolbar --}}
    <div class="no-print">
        Ticket listo —
        <button onclick="window.print()">🖨 Imprimir</button>
        <a href="{{ route('admin.pos') }}">← Nueva Venta</a>
    </div>

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ asset('images/logo-final.png') }}" alt="Logo">
        <p class="biz-name">Materiales para Construccion<br>y Ferreteria Velazquez</p>
        <p class="biz-info">Av. Gral. Vicente Guerrero<br>Frente a la Iglesia de Sta. Maria de la Asuncion</p>
        <p class="biz-info">Tel: 736 366 2326 | WA: 733 170 3671</p>
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
        @if($sale->user)<p>Atendio: {{ $sale->user->name }}</p>@endif
    </div>

    {{-- PRODUCTOS --}}
    <table>
        <thead>
            <tr>
                <th style="width:48%">Producto</th>
                <th class="text-center" style="width:10%">Cant</th>
                <th class="text-right" style="width:20%">P.Unit</th>
                <th class="text-right" style="width:22%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                @php
                    $name    = optional($item->product)->name ?? 'N/A';
                    $code    = optional($item->product)->internal_code ?? '';
                    $display = strlen($name) > 26
                        ? ($code ? $code . ' ' . substr($name, 0, 16) . '..' : substr($name, 0, 26) . '..')
                        : $name;
                @endphp
                <tr>
                    <td>{{ $display }}</td>
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
            $taxRate   = optional($item->product)->taxes_percent ? ($item->product->taxes_percent / 100) : 0.16;
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
    <div class="section" style="margin-top:4pt;">
        <div class="section-title">Forma de Pago</div>
        @if($sale->payments->count() > 0)
            @php $totalPaid = 0; @endphp
            @foreach($sale->payments as $payment)
                @php $totalPaid += $payment->amount; @endphp
                <p>{{ ucfirst(__($payment->method)) }}: ${{ number_format($payment->amount, 2) }}</p>
            @endforeach
            @php $change = $totalPaid - $sale->total; @endphp
            @if($change > 0.01)<p>Cambio: ${{ number_format($change, 2) }}</p>@endif
        @else
            <p>{{ ucfirst(__($sale->payment_method)) }}</p>
        @endif
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <p>Gracias por su compra — Conserve su ticket</p>
    </div>

    {{--
        AUTO-PRINT:
        - Waits for the logo image to fully load before triggering.
        - Uses matchMedia to detect if the browser is currently printing
          to avoid double-triggering on some browsers.
    --}}
    <script>
        var printed = false;
        function doPrint() {
            if (printed) return;
            printed = true;
            window.print();
        }

        var logo = document.querySelector('img');
        if (logo && !logo.complete) {
            logo.addEventListener('load', function () { setTimeout(doPrint, 300); });
            logo.addEventListener('error', function () { setTimeout(doPrint, 300); });
        } else {
            setTimeout(doPrint, 400);
        }

        // After the print dialog closes, focus the "Nueva Venta" link so user
        // can press Enter to go back to the POS immediately.
        window.addEventListener('afterprint', function () {
            var link = document.querySelector('.no-print a');
            if (link) link.focus();
        });
    </script>

</body>
</html>

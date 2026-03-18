<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $sale->type == 'invoice' ? 'Factura' : 'Ticket de Venta' }} #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a1a2e;
            margin: 0;
            padding: 10px 12px;
            background: #fff;
        }

        /* ── HEADER ── */
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a3a5c;
            margin-bottom: 12px;
        }

        .header img {
            width: 90px;
            margin-bottom: 4px;
        }

        .header .biz-name {
            font-size: 13px;
            font-weight: bold;
            color: #1a3a5c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 2px 0;
        }

        .header .biz-sub {
            font-size: 9px;
            color: #c59b17;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 5px 0;
        }

        .header .biz-info {
            font-size: 9px;
            color: #555;
            margin: 1px 0;
        }

        /* ── FOLIO BADGE ── */
        .folio-bar {
            background: #1a3a5c;
            color: #fff;
            text-align: center;
            padding: 4px 0;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-bottom: 10px;
            border-radius: 2px;
        }

        /* ── INFO SECTIONS ── */
        .section-title {
            font-size: 8px;
            font-weight: bold;
            color: #c59b17;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 2px;
            margin: 8px 0 4px 0;
        }

        .info-row {
            font-size: 10px;
            color: #333;
            margin: 2px 0;
        }

        .info-row span {
            font-weight: bold;
            color: #1a3a5c;
        }

        /* ── PRODUCTS TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        thead tr {
            background: #1a3a5c;
            color: #fff;
        }

        th {
            font-size: 9px;
            text-transform: uppercase;
            padding: 5px 4px;
            text-align: left;
            font-weight: bold;
        }

        tbody tr {
            border-bottom: 1px solid #eef0f4;
        }

        tbody tr:nth-child(even) {
            background: #f7f9fc;
        }

        td {
            font-size: 10px;
            padding: 5px 4px;
            color: #333;
            vertical-align: top;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        td.price {
            font-weight: bold;
            color: #1a3a5c;
        }

        /* ── TOTALS ── */
        .totals {
            margin-top: 8px;
            border-top: 2px solid #1a3a5c;
            padding-top: 6px;
        }

        .totals-row {
            display: table;
            width: 100%;
            font-size: 10px;
            margin-bottom: 2px;
        }

        .totals-row .label {
            display: table-cell;
            text-align: right;
            color: #555;
            padding-right: 8px;
        }

        .totals-row .value {
            display: table-cell;
            text-align: right;
            width: 90px;
            color: #333;
            font-weight: bold;
        }

        .grand-total-row {
            display: table;
            width: 100%;
            margin-top: 5px;
            background: #1a3a5c;
            color: #fff;
            border-radius: 2px;
            padding: 5px 0;
        }

        .grand-total-row .label {
            display: table-cell;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            padding-right: 8px;
        }

        .grand-total-row .value {
            display: table-cell;
            text-align: right;
            width: 90px;
            font-size: 13px;
            font-weight: bold;
            color: #f0c040;
            padding-right: 4px;
        }

        /* ── PAYMENTS ── */
        .payments-box {
            background: #f0f4f8;
            border-left: 3px solid #c59b17;
            padding: 5px 8px;
            margin-top: 8px;
            border-radius: 0 2px 2px 0;
        }

        .payments-box p {
            margin: 1px 0;
            font-size: 10px;
            color: #333;
        }

        /* ── CHANGE ── */
        .change-box {
            text-align: right;
            font-size: 10px;
            color: #555;
            margin-top: 3px;
        }

        .change-box strong {
            color: #1a7a3c;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 16px;
            text-align: center;
            border-top: 1px dashed #ccc;
            padding-top: 8px;
        }

        .footer .thanks {
            font-size: 12px;
            font-weight: bold;
            color: #1a3a5c;
        }

        .footer .tagline {
            font-size: 9px;
            color: #777;
            margin: 2px 0;
        }

        .footer .whatsapp {
            font-size: 9px;
            color: #1a7a3c;
            font-weight: bold;
        }

        .seller-line {
            font-size: 9px;
            color: #aaa;
            margin-top: 6px;
        }
    </style>
</head>

<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <img src="{{ public_path('images/logo-final.png') }}" alt="Logo Velazquez">
        <p class="biz-name">Materiales para Construccion y Ferreteria Velazquez</p>
        <p class="biz-sub">Tu ferretería de confianza</p>
        <p class="biz-info">📍 Av. Gral. Vicente Guerrero — Frente a la Iglesia de Santa Maria de la Asuncion</p>
        <p class="biz-info">📞 736 366 2326 &nbsp;|&nbsp; 733 170 3671</p>
    </div>

    {{-- ── FOLIO BAR ── --}}
    <div class="folio-bar">
        {{ $sale->type == 'invoice' ? 'FACTURA' : 'TICKET DE VENTA' }}
        &nbsp;#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
        &nbsp;·&nbsp;
        {{ \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i') }}
    </div>

    {{-- ── CLIENTE ── --}}
    <div class="section-title">Datos del Cliente</div>
    @if($sale->client)
        <div class="info-row">{{ $sale->client->name }}</div>
        @if($sale->client->rfc)
            <div class="info-row">RFC: <span>{{ $sale->client->rfc }}</span></div>
        @endif
        @if($sale->client->address)
            <div class="info-row">{{ $sale->client->address }}</div>
        @endif
        @if($sale->client->phone)
            <div class="info-row">Tel: {{ $sale->client->phone }}</div>
        @endif
    @else
        <div class="info-row"><span>Público en General</span></div>
    @endif

    {{-- ── METADATOS ── --}}
    <div class="section-title" style="margin-top:10px;">Detalle de la Venta</div>
    <div class="info-row">Tipo: <span>{{ $sale->type == 'invoice' ? 'Factura' : 'Ticket' }}</span></div>
    @if($sale->user)
        <div class="info-row">Atendido por: <span>{{ $sale->user->name }}</span></div>
    @endif

    {{-- ── PRODUCTOS ── --}}
    <table>
        <thead>
            <tr>
                <th style="width:55%">Producto</th>
                <th class="text-center" style="width:12%">Cant.</th>
                <th class="text-right" style="width:16%">P. Unit.</th>
                <th class="text-right" style="width:17%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name ?? 'Producto eliminado' }}
                        @if($sale->type == 'invoice' && isset($item->product->internal_code))
                            <br><small style="color:#888;">{{ $item->product->internal_code }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right price">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── TOTALES ── --}}
    @php
        $subtotal  = 0;
        $taxAmount = 0;

        foreach ($sale->items as $item) {
            $taxRate  = $item->product ? ($item->product->taxes_percent / 100) : 0.16;
            $itemBase = $item->total / (1 + $taxRate);
            $itemTax  = $item->total - $itemBase;
            $subtotal  += $itemBase;
            $taxAmount += $itemTax;
        }
    @endphp

    <div class="totals">
        <div class="totals-row">
            <span class="label">Subtotal:</span>
            <span class="value">${{ number_format($subtotal, 2) }}</span>
        </div>
        <div class="totals-row">
            <span class="label">IVA (incluido):</span>
            <span class="value">${{ number_format($taxAmount, 2) }}</span>
        </div>
        <div class="grand-total-row">
            <span class="label">TOTAL</span>
            <span class="value">${{ number_format($sale->total, 2) }}</span>
        </div>
    </div>

    {{-- ── PAGOS ── --}}
    @if($sale->payments->count() > 0)
        <div class="payments-box" style="margin-top:10px;">
            <div class="section-title" style="margin:0 0 4px 0; border:none;">Forma de Pago</div>
            @php $totalPaid = 0; @endphp
            @foreach($sale->payments as $payment)
                @php $totalPaid += $payment->amount; @endphp
                <p>
                    {{ ucfirst(__($payment->method)) }}:
                    <strong>${{ number_format($payment->amount, 2) }}</strong>
                </p>
            @endforeach
        </div>
        @php $change = $totalPaid - $sale->total; @endphp
        @if($change > 0.01)
            <div class="change-box">
                Cambio: <strong>${{ number_format($change, 2) }}</strong>
            </div>
        @endif
    @else
        <div class="payments-box" style="margin-top:10px;">
            <div class="section-title" style="margin:0 0 4px 0; border:none;">Forma de Pago</div>
            <p>{{ ucfirst(__($sale->payment_method)) }}</p>
        </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <p class="thanks">¡Gracias por su compra!</p>
        <p class="tagline">Si tiene algún problema con su compra, preséntese con este ticket.</p>
        <p class="whatsapp">📲 WhatsApp: 733 170 3671</p>
        <p class="seller-line">
            Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </p>
    </div>

</body>
</html>
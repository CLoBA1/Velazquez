<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Imprimiendo ticket...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #1a3a5c;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 40px 50px;
            text-align: center;
            max-width: 380px;
        }
        .spinner {
            width: 48px; height: 48px;
            border: 5px solid rgba(255,255,255,0.2);
            border-top-color: #f0c040;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        h2 { margin: 0 0 8px; font-size: 20px; }
        p  { margin: 0; font-size: 13px; opacity: 0.7; }
        .actions { margin-top: 28px; display: flex; gap: 10px; flex-direction: column; }
        .btn {
            padding: 10px 20px; border-radius: 6px; border: none;
            font-size: 14px; font-weight: bold; cursor: pointer;
        }
        .btn-primary { background: #f0c040; color: #1a3a5c; }
        .btn-secondary { background: rgba(255,255,255,0.15); color: #fff; }
        /* hidden iframe that will hold the PDF */
        #pdfFrame { display: none; position: fixed; top:0; left:0; width:100%; height:100%; }
    </style>
</head>
<body>
    <div class="card">
        <div class="spinner" id="spinner"></div>
        <h2 id="status-title">Generando ticket...</h2>
        <p id="status-msg">Por favor espere un momento</p>
        <div class="actions" id="actions" style="display:none !important;">
            <button class="btn btn-primary" onclick="triggerPrint()">🖨 Imprimir de nuevo</button>
            <a href="{{ route('admin.pos') }}" class="btn btn-secondary">← Nueva Venta</a>
        </div>
    </div>

    <iframe id="pdfFrame"></iframe>

    <script>
        var pdfUrl  = '{{ route("admin.sales.pdf", $sale->id) }}';
        var posUrl  = '{{ route("admin.pos") }}';
        var frame   = document.getElementById('pdfFrame');
        var spinner = document.getElementById('spinner');
        var title   = document.getElementById('status-title');
        var msg     = document.getElementById('status-msg');
        var actions = document.getElementById('actions');

        function showActions() {
            spinner.style.display = 'none';
            title.textContent = '¡Ticket listo!';
            msg.textContent   = 'Puede cerrar esta ventana o volver al POS.';
            actions.style.cssText = 'display:flex !important;';
        }

        function triggerPrint() {
            try {
                frame.style.display = 'block';
                frame.contentWindow.focus();
                frame.contentWindow.print();
                frame.style.display = 'none';
            } catch(e) {
                // Fallback: open PDF in new tab
                window.open(pdfUrl, '_blank');
            }
        }

        // Step 1: fetch the PDF as a blob so the browser handles it locally (same-origin)
        fetch(pdfUrl, { credentials: 'same-origin' })
            .then(function(resp) {
                if (!resp.ok) throw new Error('Error al generar PDF');
                return resp.blob();
            })
            .then(function(blob) {
                var objectUrl = URL.createObjectURL(blob);

                // Step 2: load blob into hidden iframe
                frame.src = objectUrl;

                frame.onload = function() {
                    title.textContent = 'Abriendo diálogo...';

                    // Step 3: short delay then auto-print from iframe
                    setTimeout(function() {
                        try {
                            frame.style.display = 'block';
                            frame.contentWindow.focus();
                            frame.contentWindow.print();
                            frame.style.display = 'none';
                        } catch(e) {
                            // If iframe print blocked, open in new tab as fallback
                            window.open(objectUrl, '_blank');
                        }
                        showActions();
                    }, 800);
                };
            })
            .catch(function(err) {
                // Hard fallback: just open the PDF directly
                title.textContent = 'Abriendo ticket...';
                msg.textContent   = 'Si no se abre solo, use el botón de abajo.';
                spinner.style.display = 'none';
                window.open(pdfUrl, '_blank');
                showActions();
            });

        // After print dialog closes → show actions
        window.addEventListener('afterprint', showActions);
    </script>
</body>
</html>

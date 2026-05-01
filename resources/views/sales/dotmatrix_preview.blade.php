<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Preview Dot Matrix - {{ $transaction->reference_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #1a1a2e;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .toolbar {
            background: #16213e;
            border-radius: 12px;
            padding: 12px 20px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .toolbar button {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-rawbt {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        .btn-rawbt:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .btn-download {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }

        .btn-download:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-back {
            background: #374151;
            color: #d1d5db;
        }

        .btn-back:hover {
            background: #4b5563;
        }

        .info-badge {
            background: #0f3460;
            color: #7dd3fc;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
        }

        .paper {
            background: #fffff0;
            border: 1px solid #ccc;
            padding: 20px 24px;
            max-width: 720px;
            width: 100%;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            position: relative;
        }

        /* Simulated perforated edges for continuous paper */
        .paper::before,
        .paper::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 16px;
            background: repeating-linear-gradient(to bottom, transparent 0px, transparent 12px, #ddd 12px, #ddd 14px);
        }

        .paper::before {
            left: 0;
            border-right: 1px dashed #bbb;
        }

        .paper::after {
            right: 0;
            border-left: 1px dashed #bbb;
        }

        .paper pre {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #1a1a1a;
            white-space: pre;
            overflow-x: auto;
            margin: 0;
            padding: 0 20px;
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <a href="{{ route('sales.index') }}" class="btn-back">← Kembali</a>
        <span class="info-badge">{{ $transaction->reference_code }} — Preview Dot Matrix (80 kolom)</span>

        <button class="btn-rawbt" onclick="sendToRawBT()">🖨️ Kirim ke RawBT</button>
        <button class="btn-download" onclick="downloadPrn()">⬇️ Download .PRN</button>
    </div>

    <div class="paper">
        <pre>{{ $preview }}</pre>
    </div>

    <script>
        const base64Data = @json($base64);
        const refCode = @json($transaction->reference_code);

        function sendToRawBT() {
            const rawbtUri = `rawbt:base64,${base64Data}`;
            window.location.href = rawbtUri;
        }

        function downloadPrn() {
            const rawBytes = atob(base64Data);
            const bytes = new Uint8Array(rawBytes.length);
            for (let i = 0; i < rawBytes.length; i++) {
                bytes[i] = rawBytes.charCodeAt(i);
            }
            const blob = new Blob([bytes], { type: 'application/octet-stream' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `nota-${refCode}.prn`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    </script>
</body>

</html>

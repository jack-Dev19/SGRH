<!DOCTYPE html>
<html>
<head>
    <title>Impression — {{ $contrat->numero }}</title>
    <style>
        * { margin: 0; padding: 0; }
        iframe { width: 100vw; height: 100vh; border: none; }
    </style>
</head>
<body>
    <iframe src="{{ route('gestion.contrats.preview', $contrat) }}#toolbar=0"
            id="pdfFrame"></iframe>

    <script>
        document.getElementById('pdfFrame').onload = function () {
            setTimeout(function () {
                try {
                    document.getElementById('pdfFrame').contentWindow.print();
                } catch (e) {
                    // fallback si le navigateur bloque
                    window.print();
                }
            }, 1000);
        };
    </script>
</body>
</html>
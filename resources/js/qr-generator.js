import QRCode from 'qrcode';

document.addEventListener('DOMContentLoaded', () => {

    const qrCanvases = document.querySelectorAll('.employee-qr');

    if (qrCanvases.length === 0) {
        return;
    }

    qrCanvases.forEach((canvas) => {

        const code = canvas.dataset.code?.trim();

        if (!code) {
            console.error('Code employé manquant.');
            return;
        }

        QRCode.toCanvas(
            canvas,
            code,
            {
                width: 300,
                margin: 2,
                errorCorrectionLevel: 'H',
                color: {
                    dark:  '#1e293b',
                    light: '#ffffff',
                },
            },
            (error) => {
                if (error) {
                    console.error('Erreur QR Code:', error);
                    return;
                }
                console.log('✅ QR Code généré:', code);
            }
        );
    });

    console.log('✅ QR Generator JavaScript chargé.');
});
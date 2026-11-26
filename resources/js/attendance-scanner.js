import { Html5Qrcode } from 'html5-qrcode';

document.addEventListener('DOMContentLoaded', () => {
    const reader = document.getElementById('reader');
    const startButton = document.getElementById('start-scanner');
    const stopButton = document.getElementById('stop-scanner');
    const result = document.getElementById('scan-result');
    const scannerContainer = document.getElementById('attendance-scanner');

    if (!reader || !startButton || !stopButton || !result || !scannerContainer) {
        return;
    }

    const scanUrl = scannerContainer.dataset.scanUrl;

    let scanner = null;
    let scanning = false;
    let processing = false;
    let lastCode = null;
    let lastScanTime = 0;

    // ============================================================
    // SÉCURISER LE TEXTE AVANT DE L'INSÉRER DANS LE HTML
    // ============================================================
    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, (character) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }[character]));
    }

    // ============================================================
    // AFFICHER UN RÉSULTAT
    // ============================================================
    function showResult(message, type = 'success') {
        result.classList.remove(
            'hidden',
            'bg-green-100', 'text-green-800',
            'bg-red-100', 'text-red-800',
            'bg-yellow-100', 'text-yellow-800',
            'bg-blue-100', 'text-blue-800',
            'bg-orange-100', 'text-orange-800'
        );

        const colors = {
            success: ['bg-green-100', 'text-green-800'],
            warning: ['bg-yellow-100', 'text-yellow-800'],
            info: ['bg-blue-100', 'text-blue-800'],
            error: ['bg-red-100', 'text-red-800'],
            too_early: ['bg-orange-100', 'text-orange-800'],
        };

        const [background, textColor] = colors[type] || colors.info;

        result.classList.add(background, textColor);
        result.innerHTML = message;
    }

    // ============================================================
    // AFFICHER UN TOAST
    // ============================================================
    function showToast(title, message = '', type = 'success', duration = 5000) {
        let container = document.getElementById('toast-container');

        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-6 right-6 z-50 space-y-3 max-w-md';
            document.body.appendChild(container);
        }

        const config = {
            success: { bg: 'bg-emerald-500', icon: '✓' },
            error: { bg: 'bg-red-500', icon: '✕' },
            warning: { bg: 'bg-amber-500', icon: '⚠' },
            info: { bg: 'bg-blue-500', icon: 'ℹ' },
            too_early: { bg: 'bg-orange-500', icon: '⏱️' },
        };

        const toastConfig = config[type] || config.info;
        const safeTitle = escapeHtml(title);
        const safeMessage = escapeHtml(message);

        const toast = document.createElement('div');
        toast.className =
            'toast-item transform translate-x-full transition-all duration-500 ease-out opacity-0';

        toast.innerHTML = `
            <div class="flex items-start gap-3 min-w-[320px] max-w-md ${toastConfig.bg} text-white rounded-2xl shadow-2xl p-4">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-lg font-bold shrink-0">
                    ${toastConfig.icon}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm leading-tight mb-0.5">${safeTitle}</p>
                    ${safeMessage ? `<p class="text-xs opacity-90 leading-tight">${safeMessage}</p>` : ''}
                </div>
                <button
                    type="button"
                    class="toast-close text-white/70 hover:text-white transition shrink-0 text-lg leading-none"
                    aria-label="Fermer"
                >×</button>
            </div>
        `;

        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.remove();
        });

        container.appendChild(toast);

        requestAnimationFrame(() => {
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);
        });

        playBeep(type);

        setTimeout(() => {
            toast.classList.remove('translate-x-0', 'opacity-100');
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, duration);
    }

    // ============================================================
    // SON
    // ============================================================
    function playBeep(type = 'info') {
        try {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;

            if (!AudioContextClass) {
                return;
            }

            const audioContext = new AudioContextClass();

            const sounds = {
                success: { frequency: 800, duration: 0.15, repeat: 2 },
                error: { frequency: 250, duration: 0.4, repeat: 1 },
                warning: { frequency: 500, duration: 0.2, repeat: 2 },
                info: { frequency: 600, duration: 0.1, repeat: 1 },
                too_early: { frequency: 400, duration: 0.3, repeat: 2 },
            };

            const sound = sounds[type] || sounds.info;

            for (let i = 0; i < sound.repeat; i++) {
                const startAt =
                    audioContext.currentTime + i * (sound.duration + 0.05);

                const oscillator = audioContext.createOscillator();
                const gain = audioContext.createGain();

                oscillator.connect(gain);
                gain.connect(audioContext.destination);

                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(sound.frequency, startAt);

                gain.gain.setValueAtTime(0, startAt);
                gain.gain.linearRampToValueAtTime(0.15, startAt + 0.01);
                gain.gain.exponentialRampToValueAtTime(
                    0.001,
                    startAt + sound.duration
                );

                oscillator.start(startAt);
                oscillator.stop(startAt + sound.duration);
            }

            setTimeout(() => audioContext.close(), 1500);
        } catch (error) {
            console.warn('Son indisponible :', error);
        }
    }

    // ============================================================
    // FLASH VISUEL
    // ============================================================
    function flashScreen(color = 'emerald') {
        const colors = {
            emerald: '#10b981',
            red: '#ef4444',
            orange: '#f97316',
        };

        const flash = document.createElement('div');
        flash.className =
            'fixed inset-0 pointer-events-none z-40 transition-opacity duration-300';
        flash.style.background = colors[color] || colors.emerald;
        flash.style.opacity = '0.3';

        document.body.appendChild(flash);

        setTimeout(() => {
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 300);
        }, 100);
    }

    // ============================================================
    // ENVOYER LE CODE QR AU CONTRÔLEUR LARAVEL
    // ============================================================
    async function sendQRCode(code) {
        if (processing) {
            return;
        }

        processing = true;

        try {
            if (!scanUrl) {
                throw new Error(
                    'URL du pointage introuvable dans la vue du scanner.'
                );
            }

            const csrfToken =
                scannerContainer.dataset.csrfToken ||
                document.querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');

            if (!csrfToken) {
                throw new Error(
                    'Token CSRF introuvable. Vérifiez la vue du scanner et le layout.'
                );
            }

            const response = await fetch(scanUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    employee_code: code,
                }),
            });

            const responseText = await response.text();
            let data;

            try {
                data = JSON.parse(responseText);
            } catch {
                throw new Error(
                    `Réponse non JSON du serveur (HTTP ${response.status}). Consultez les logs Laravel.`
                );
            }

            // Le contrôleur renvoie 429 pour un départ trop tôt.
            if (data.type === 'too_early') {
                const message =
                    data.message ||
                    'Veuillez patienter avant de pointer votre départ.';

                const remaining = Number(data.minutes_restantes);
                const detail =
                    Number.isFinite(remaining) && remaining > 0
                        ? ` Temps restant : environ ${remaining} minute(s).`
                        : '';

                showResult(
                    `<div class="text-center">
                        <div class="text-4xl mb-3">⏱️</div>
                        <h2 class="text-xl font-bold">Départ trop tôt</h2>
                        <p class="mt-2 text-sm">${escapeHtml(message + detail)}</p>
                    </div>`,
                    'too_early'
                );

                showToast(
                    'Départ trop tôt',
                    message + detail,
                    'too_early',
                    7000
                );

                flashScreen('orange');
                return;
            }

            // L'arrivée et le départ sont déjà enregistrés.
            if (data.type === 'already_completed') {
                const message =
                    data.message ||
                    'Arrivée et départ déjà enregistrés.';

                showResult(escapeHtml(message), 'warning');
                showToast('Pointage déjà terminé', message, 'warning');
                return;
            }

            // Erreurs HTTP ou refus métier renvoyés par Laravel.
            if (!response.ok || data.success !== true) {
                const message =
                    data.message ||
                    `Le serveur a répondu avec le statut HTTP ${response.status}.`;

                showResult(
                    `<div class="text-center">
                        <div class="text-4xl mb-3">❌</div>
                        <h2 class="text-xl font-bold">Pointage refusé</h2>
                        <p class="mt-2 text-sm">${escapeHtml(message)}</p>
                    </div>`,
                    'error'
                );

                showToast('Pointage refusé', message, 'error', 8000);
                flashScreen('red');
                return;
            }

            // Réponses de succès du PointageController.
            const message = data.message || 'Pointage enregistré.';
            const heure = data.heure ? `Heure : ${data.heure}` : '';

            let title = 'Pointage enregistré';
            let icon = '✅';

            if (data.type === 'check_in') {
                title = 'Arrivée enregistrée';
                icon = '🟢';
            } else if (data.type === 'check_out') {
                title = 'Départ enregistré';
                icon = '🔵';
            }

            showResult(
                `<div class="text-center">
                    <div class="text-4xl mb-3">${icon}</div>
                    <h2 class="text-xl font-bold">${escapeHtml(title)}</h2>
                    <p class="mt-2 text-sm">${escapeHtml(message)}</p>
                    ${heure ? `<p class="mt-1 text-sm font-semibold">${escapeHtml(heure)}</p>` : ''}
                </div>`,
                'success'
            );

            showToast(
                title,
                [message, heure].filter(Boolean).join(' — '),
                'success'
            );

            flashScreen('emerald');

        } catch (error) {
            console.error('Erreur réelle du pointage :', error);

            const message =
                error instanceof Error
                    ? error.message
                    : 'Une erreur inconnue est survenue.';

            showResult(
                `<div class="text-center">
                    <div class="text-4xl mb-3">❌</div>
                    <h2 class="text-xl font-bold">Échec du pointage</h2>
                    <p class="mt-2 text-sm">${escapeHtml(message)}</p>
                </div>`,
                'error'
            );

            showToast('Échec du pointage', message, 'error', 8000);
            flashScreen('red');

        } finally {
            // Empêche les lectures répétées pendant 3 secondes.
            setTimeout(() => {
                processing = false;
            }, 3000);
        }
    }

    // ============================================================
    // DÉMARRER LE SCANNER
    // ============================================================
    async function startScanner() {
        if (scanning) {
            return;
        }

        try {
            showResult('📷 Démarrage de la caméra...', 'info');

            scanner = new Html5Qrcode('reader');

            await scanner.start(
                { facingMode: 'environment' },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                },
                async (decodedText) => {
                    const now = Date.now();
                    const code = decodedText.trim();

                    if (!code) {
                        return;
                    }

                    // Ignore les lectures répétées du même QR pendant 3 secondes.
                    if (code === lastCode && now - lastScanTime < 3000) {
                        return;
                    }

                    // Ignore toute lecture pendant le traitement du pointage.
                    if (processing) {
                        return;
                    }

                    lastCode = code;
                    lastScanTime = now;

                    await sendQRCode(code);
                },
                () => {}
            );

            scanning = true;

            startButton.classList.add('hidden');
            stopButton.classList.remove('hidden');

            showResult(
                `<div class="text-center">
                    <div class="text-4xl mb-2">📷</div>
                    <strong>Caméra active</strong>
                    <p class="mt-2">Placez le QR Code devant la caméra.</p>
                </div>`,
                'info'
            );

            showToast(
                'Caméra activée',
                'Présentez un QR code.',
                'info',
                3000
            );

        } catch (error) {
            console.error('Erreur caméra :', error);

            let message = 'Impossible de démarrer la caméra.';

            if (error.name === 'NotAllowedError') {
                message = 'Autorisation d’accès à la caméra refusée.';
            } else if (error.name === 'NotFoundError') {
                message = 'Aucune caméra détectée.';
            } else if (error.name === 'NotReadableError') {
                message =
                    'La caméra est déjà utilisée par une autre application.';
            }

            showResult(
                `<div class="text-center">
                    <div class="text-4xl mb-3">❌</div>
                    <h2 class="text-xl font-bold">Erreur caméra</h2>
                    <p class="mt-3">${escapeHtml(message)}</p>
                </div>`,
                'error'
            );

            showToast('Erreur caméra', message, 'error', 6000);
        }
    }

    // ============================================================
    // ARRÊTER LE SCANNER
    // ============================================================
    async function stopScanner() {
        if (!scanner || !scanning) {
            return;
        }

        try {
            await scanner.stop();
            scanner.clear();
        } catch (error) {
            console.warn('Arrêt du scanner :', error);
        }

        scanner = null;
        scanning = false;
        processing = false;

        startButton.classList.remove('hidden');
        stopButton.classList.add('hidden');

        showResult('📷 Caméra arrêtée.', 'warning');
    }

    startButton.addEventListener('click', startScanner);
    stopButton.addEventListener('click', stopScanner);

    console.log('Scanner de pointage QR chargé.');
});
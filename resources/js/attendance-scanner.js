import { Html5Qrcode } from 'html5-qrcode';

document.addEventListener('DOMContentLoaded', () => {

    const reader           = document.getElementById('reader');
    const startButton      = document.getElementById('start-scanner');
    const stopButton       = document.getElementById('stop-scanner');
    const result           = document.getElementById('scan-result');
    const scannerContainer = document.getElementById('attendance-scanner');

    if (!reader || !startButton || !stopButton || !result || !scannerContainer) return;

    const scanUrl = scannerContainer.dataset.scanUrl;

    let scanner      = null;
    let scanning     = false;
    let processing   = false;
    let lastCode     = null;
    let lastScanTime = 0;

    // ============================================================
    // AFFICHER UN RÉSULTAT
    // ============================================================
    function showResult(message, type = 'success') {
        result.classList.remove(
            'hidden',
            'bg-green-100',  'text-green-800',
            'bg-red-100',    'text-red-800',
            'bg-yellow-100', 'text-yellow-800',
            'bg-blue-100',   'text-blue-800',
            'bg-orange-100', 'text-orange-800'
        );

        const colors = {
            'success': ['bg-green-100', 'text-green-800'],
            'warning': ['bg-yellow-100', 'text-yellow-800'],
            'info':    ['bg-blue-100', 'text-blue-800'],
            'error':   ['bg-red-100', 'text-red-800'],
            'too_early': ['bg-orange-100', 'text-orange-800'],
        };

        const [bg, text] = colors[type] || colors.info;
        result.classList.add(bg, text);
        result.innerHTML = message;
    }

    // ============================================================
    // TOAST
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
            'success':   { bg: 'bg-emerald-500', icon: '✓' },
            'error':     { bg: 'bg-red-500',     icon: '✕' },
            'warning':   { bg: 'bg-amber-500',   icon: '⚠' },
            'info':      { bg: 'bg-blue-500',    icon: 'ℹ' },
            'too_early': { bg: 'bg-orange-500',  icon: '⏱️' },
        };

        const c = config[type] || config.info;

        const toast = document.createElement('div');
        toast.className = 'toast-item transform translate-x-full transition-all duration-500 ease-out opacity-0';
        toast.innerHTML = `
            <div class="flex items-start gap-3 min-w-[320px] max-w-md ${c.bg} text-white rounded-2xl shadow-2xl p-4">
                <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-lg font-bold shrink-0">
                    ${c.icon}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm leading-tight mb-0.5">${title}</p>
                    ${message ? `<p class="text-xs opacity-90 leading-tight">${message}</p>` : ''}
                </div>
                <button onclick="this.closest('.toast-item').remove()"
                    class="text-white/70 hover:text-white transition shrink-0 text-lg leading-none">×</button>
            </div>
        `;

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
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const sounds = {
                'success':   { freq: 800, duration: 0.15, repeat: 2 },
                'error':     { freq: 250, duration: 0.4,  repeat: 1 },
                'warning':   { freq: 500, duration: 0.2,  repeat: 2 },
                'info':      { freq: 600, duration: 0.1,  repeat: 1 },
                'too_early': { freq: 400, duration: 0.3,  repeat: 2 },
            };
            const s = sounds[type] || sounds.info;

            for (let i = 0; i < s.repeat; i++) {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.type = 'sine';
                osc.frequency.setValueAtTime(s.freq, audioCtx.currentTime + i * (s.duration + 0.05));
                gain.gain.setValueAtTime(0, audioCtx.currentTime + i * (s.duration + 0.05));
                gain.gain.linearRampToValueAtTime(0.15, audioCtx.currentTime + i * (s.duration + 0.05) + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + i * (s.duration + 0.05) + s.duration);
                osc.start(audioCtx.currentTime + i * (s.duration + 0.05));
                osc.stop(audioCtx.currentTime + i * (s.duration + 0.05) + s.duration);
            }
        } catch (e) {}
    }

    // ============================================================
    // FLASH
    // ============================================================
    function flashScreen(color = 'emerald') {
        const colors = { emerald: '#10b981', red: '#ef4444', orange: '#f97316' };
        const flash = document.createElement('div');
        flash.className = 'fixed inset-0 pointer-events-none z-40 transition-opacity duration-300';
        flash.style.background = colors[color] || colors.emerald;
        flash.style.opacity = '0.3';
        document.body.appendChild(flash);
        setTimeout(() => {
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 300);
        }, 100);
    }

    // ============================================================
    // ENVOYER LE CODE
    // ============================================================
    async function sendQRCode(code) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch(scanUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ employee_code: code }),
            });

            const data = await response.json();
            console.log('Réponse Laravel:', data);

            // ============================================
            // SUCCÈS
            // ============================================
            if (data.success) {

                // CHECK-IN
                if (data.type === 'check_in') {
                    showResult(`
                        <div class="text-center">
                            <div class="text-5xl mb-3">✅</div>
                            <h2 class="text-2xl font-bold">CHECK-IN</h2>
                            <p class="text-lg mt-3">${data.employee.prenom} ${data.employee.nom}</p>
                            <p class="mt-2">Code : <strong>${data.employee.code}</strong></p>
                            <p class="mt-2">Heure d'arrivée : <strong>${data.heure}</strong></p>
                            <p class="mt-3 text-sm bg-yellow-100 text-yellow-800 rounded-lg p-2">
                                ⏱️ ${data.message_extra}
                            </p>
                        </div>
                    `, 'success');

                    showToast(
                        '✓ Arrivée enregistrée',
                        `${data.employee.prenom} ${data.employee.nom} — ${data.heure}`,
                        'success',
                        6000
                    );
                }

                // CHECK-OUT
                else if (data.type === 'check_out') {
                    showResult(`
                        <div class="text-center">
                            <div class="text-5xl mb-3">✅</div>
                            <h2 class="text-2xl font-bold">CHECK-OUT</h2>
                            <p class="text-lg mt-3">${data.employee.prenom} ${data.employee.nom}</p>
                            <p class="mt-2">Code : <strong>${data.employee.code}</strong></p>
                            <p class="mt-2">Arrivée : <strong>${data.heure_arrivee}</strong></p>
                            <p class="mt-2">Départ : <strong>${data.heure_depart}</strong></p>
                            <p class="mt-3 text-sm bg-emerald-100 text-emerald-800 rounded-lg p-2">
                                ⏱️ Durée : <strong>${data.duree_heures}h</strong>
                            </p>
                        </div>
                    `, 'success');

                    showToast(
                        '✓ Départ enregistré',
                        `${data.employee.prenom} ${data.employee.nom} — Durée : ${data.duree_heures}h`,
                        'success',
                        6000
                    );
                }

                flashScreen('emerald');
            }

            // ============================================
            // ÉCHEC
            // ============================================
            else {

                // ⭐ CAS SPÉCIAL : Check-out trop tôt
                if (data.type === 'too_early') {
                    showResult(`
                        <div class="text-center">
                            <div class="text-5xl mb-3">⏱️</div>
                            <h2 class="text-2xl font-bold text-orange-700">DÉLAI NON RESPECTÉ</h2>
                            <p class="text-lg mt-3">${data.message}</p>
                            <div class="mt-4 bg-orange-50 rounded-xl p-4 text-sm">
                                <p>⏰ Arrivée : <strong>${data.heure_entree}</strong></p>
                                <p class="mt-1">⏳ Temps écoulé : <strong>${data.minutes_ecoulees} min</strong></p>
                                <p class="mt-1">⌛ Temps restant : <strong>${data.minutes_restantes} min</strong></p>
                                <p class="mt-1">📌 Délai requis : <strong>${data.delai_requis} min</strong></p>
                            </div>
                        </div>
                    `, 'too_early');

                    showToast(
                        '⏱️ Patientez encore',
                        `${data.minutes_restantes} minute(s) avant le départ`,
                        'too_early',
                        8000
                    );

                    flashScreen('orange');
                }

                // Autres cas (déjà terminé, erreur, etc.)
                else {
                    showResult(`
                        <div class="text-center">
                            <div class="text-4xl mb-3">⚠️</div>
                            <h2 class="text-xl font-bold">${data.message}</h2>
                        </div>
                    `, data.type === 'already_completed' ? 'warning' : 'error');

                    showToast('Échec du pointage', data.message, data.type || 'error', 6000);
                    flashScreen('red');
                }
            }

        } catch (error) {
            console.error('Erreur communication Laravel:', error);
            showResult(`
                <div class="text-center">
                    <div class="text-4xl mb-3">❌</div>
                    <h2 class="text-xl font-bold">Erreur serveur</h2>
                </div>
            `, 'error');
            showToast('Erreur réseau', 'Vérifiez votre connexion.', 'error', 5000);
            flashScreen('red');
        }
    }

    // ============================================================
    // DÉMARRER
    // ============================================================
    async function startScanner() {
        if (scanning) return;

        try {
            showResult('📷 Démarrage de la caméra...', 'info');

            scanner = new Html5Qrcode('reader');

            await scanner.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 },
                async (decodedText) => {
                    const now = Date.now();
                    if (decodedText === lastCode && (now - lastScanTime) < 3000) return;
                    if (processing) return;

                    lastCode     = decodedText;
                    lastScanTime = now;
                    processing   = true;

                    await sendQRCode(decodedText.trim());
                    setTimeout(() => { processing = false; }, 3000);
                },
                () => {}
            );

            scanning = true;
            startButton.classList.add('hidden');
            stopButton.classList.remove('hidden');

            showResult(`
                <div class="text-center">
                    <div class="text-4xl mb-2">📷</div>
                    <strong>Caméra active</strong>
                    <p class="mt-2">Placez le QR Code devant la caméra.</p>
                </div>
            `, 'info');

            showToast('Caméra activée', 'Présentez un QR code.', 'info', 3000);

        } catch (error) {
            console.error('ERREUR CAMÉRA:', error);
            let message = 'Impossible de démarrer la caméra.';
            if (error.name === 'NotAllowedError') message = "Autorisation refusée.";
            else if (error.name === 'NotFoundError') message = "Aucune caméra détectée.";

            showResult(`
                <div class="text-center">
                    <div class="text-4xl mb-3">❌</div>
                    <h2 class="text-xl font-bold">Erreur caméra</h2>
                    <p class="mt-3">${message}</p>
                </div>
            `, 'error');
            showToast('Erreur caméra', message, 'error', 6000);
        }
    }

    // ============================================================
    // ARRÊTER
    // ============================================================
    async function stopScanner() {
        if (!scanner || !scanning) return;
        try {
            await scanner.stop();
            scanner.clear();
        } catch (error) {}
        scanner = null;
        scanning = false;
        processing = false;
        startButton.classList.remove('hidden');
        stopButton.classList.add('hidden');
        showResult('📷 Caméra arrêtée.', 'warning');
    }

    startButton.addEventListener('click', startScanner);
    stopButton.addEventListener('click', stopScanner);

    console.log('✅ Attendance scanner JavaScript chargé.');
});
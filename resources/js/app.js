import './dashboard.js';

// ⭐ Scanner QR (pointage) — utilise html5-qrcode
import './attendance-scanner';

// ⭐ Générateur QR (fiche employé) — utilise qrcode npm
import './qr-generator';

// ============================================================
// Validation globale pour les formulaires employés
// ============================================================
document.addEventListener('submit', function (e) {
    const form = e.target;

    if (!form.id || !form.id.includes('employe')) return;

    const dept  = form.querySelector('[name="departement_id"]');
    const poste = form.querySelector('[name="poste_id"]');

    if (dept && !dept.value) {
        e.preventDefault();
        alert('⚠️ Veuillez sélectionner un département.');
        return false;
    }

    if (poste && !poste.value) {
        e.preventDefault();
        alert('⚠️ Veuillez sélectionner un poste.');
        return false;
    }
});
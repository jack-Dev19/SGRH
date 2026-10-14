/**
 * Fichier bootstrap Laravel (optionnel).
 * Configure Axios pour les requêtes HTTP si besoin.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
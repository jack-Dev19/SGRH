/**
 * Chart.js — Graphiques du Dashboard Admin
 * -----------------------------------------
 * Lit les données depuis les attributs data-* des <canvas> injectés par Blade.
 *
 * Contient :
 * - Graphique barres : Congés par mois
 * - Graphique donut  : Répartition par département
 * - Graphique courbe : Évolution de l'effectif
 */

import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {

    // ============================================================
    // 1. GRAPHIQUE : Congés par mois (barres)
    // ============================================================
    const canvasConges = document.getElementById('chart-conges');

    if (canvasConges) {
        const labels = JSON.parse(canvasConges.dataset.labels || '[]');
        const values = JSON.parse(canvasConges.dataset.values || '[]');

        new Chart(canvasConges, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre de congés',
                    data: values,
                    backgroundColor: 'rgba(139, 92, 246, 0.75)',
                    hoverBackgroundColor: 'rgba(139, 92, 246, 1)',
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 36,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                const val = context.parsed.y;
                                return val + ' congé' + (val > 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#94a3b8',
                            font: { size: 11 },
                        },
                        grid: {
                            color: 'rgba(226, 232, 240, 0.5)',
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 },
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // ============================================================
    // 2. GRAPHIQUE : Répartition par département (donut)
    // ============================================================
    const canvasDepartements = document.getElementById('chart-departements');

    if (canvasDepartements) {
        const labels = JSON.parse(canvasDepartements.dataset.labels || '[]');
        const values = JSON.parse(canvasDepartements.dataset.values || '[]');

        const palette = [
            '#3b82f6', // bleu
            '#ef4444', // rouge
            '#10b981', // vert
            '#8b5cf6', // violet
            '#f59e0b', // orange
            '#ec4899', // rose
            '#06b6d4', // cyan
            '#84cc16', // lime
        ];

        new Chart(canvasDepartements, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: palette.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.parsed;
                                const pct = total > 0 ? ((value / total) * 100).toFixed(0) : 0;
                                return value + ' employé' + (value > 1 ? 's' : '') + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // ============================================================
    // 3. GRAPHIQUE : Évolution de l'effectif (courbe)
    // ============================================================
    const canvasEffectif = document.getElementById('chart-effectif');

    if (canvasEffectif) {
        const labels = JSON.parse(canvasEffectif.dataset.labels || '[]');
        const values = JSON.parse(canvasEffectif.dataset.values || '[]');

        // Dégradé pour remplir la zone sous la courbe
        const ctx = canvasEffectif.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(canvasEffectif, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Effectif',
                    data: values,
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#3b82f6',
                    pointHoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        padding: 10,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                const val = context.parsed.y;
                                return val + ' employé' + (val > 1 ? 's' : '');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#94a3b8',
                            font: { size: 11 },
                        },
                        grid: {
                            color: 'rgba(226, 232, 240, 0.5)',
                            drawBorder: false,
                        }
                    },
                    x: {
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 },
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

});
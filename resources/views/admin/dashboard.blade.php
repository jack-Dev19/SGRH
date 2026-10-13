@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')

@section('content')

{{-- ============================================= --}}
{{-- CARTES DE STATISTIQUES (5 cartes)             --}}
{{-- ============================================= --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

    {{-- Carte 1 : Total Employés --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-5 hover:shadow-xl hover:shadow-purple-500/10 hover:-translate-y-1 transition duration-300">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Employés</p>
            <div class="bg-purple-100 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900">{{ $totalEmployees }}</p>
        <p class="text-xs text-slate-500 mt-1">{{ $maleCount }}H · {{ $femaleCount }}F</p>
    </div>

    {{-- Carte 2 : Employés Actifs --}}
    <div class="bg-white rounded-2xl border border-emerald-100 p-5 hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition duration-300">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Employés Actifs</p>
            <div class="bg-emerald-100 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900">{{ $activeEmployees }}</p>
        <p class="text-xs text-slate-500 mt-1">{{ $totalEmployees - $activeEmployees }} inactifs</p>
    </div>

    {{-- Carte 3 : Congés en cours --}}
    <div class="bg-white rounded-2xl border border-amber-100 p-5 hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-1 transition duration-300">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Congés en cours</p>
            <div class="bg-amber-100 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900">{{ $pendingConges }}</p>
        <p class="text-xs text-slate-500 mt-1">en attente</p>
    </div>

    {{-- Carte 4 : Masse salariale --}}
    <div class="bg-white rounded-2xl border border-violet-100 p-5 hover:shadow-xl hover:shadow-violet-500/10 hover:-translate-y-1 transition duration-300">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Masse Salariale</p>
            <div class="bg-violet-100 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-slate-900">{{ number_format($totalSalary, 0, ',', ' ') }} <span class="text-sm">MAD</span></p>
        <p class="text-xs text-slate-500 mt-1">{{ now()->translatedFormat('F Y') }}</p>
    </div>

    {{-- Carte 5 : Recrutements --}}
    <div class="bg-white rounded-2xl border border-pink-100 p-5 hover:shadow-xl hover:shadow-pink-500/10 hover:-translate-y-1 transition duration-300">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Recrutements</p>
            <div class="bg-pink-100 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900">{{ $recruitments }}</p>
        <p class="text-xs text-slate-500 mt-1">postes ouverts</p>
    </div>

</div>

{{-- ============================================= --}}
{{-- NOTIFICATIONS (petite carte)                  --}}
{{-- ============================================= --}}
{{-- <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-red-100 p-5 hover:shadow-xl hover:shadow-red-500/10 transition duration-300">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Notifications</p>
            <div class="bg-red-100 p-2 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-extrabold text-slate-900">{{ $notifications }}</p>
        <p class="text-xs text-slate-500 mt-1">non lues</p>
    </div>
</div> --}}

{{-- ============================================= --}}
{{-- GRAPHIQUES (2 cartes côte à côte)             --}}
{{-- ============================================= --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- Graphique 1 : Congés par mois (BAR) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Congés par mois — {{ now()->year }}</h3>
        <p class="text-xs text-slate-500 mb-4">Répartition mensuelle</p>
        <div class="h-64">
            <canvas id="congesChart"></canvas>
        </div>
    </div>

    {{-- Graphique 2 : Répartition par département (DOUGHNUT) --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Répartition par département</h3>
        <p class="text-xs text-slate-500 mb-4">Effectif total : {{ $totalEmployees }}</p>
        <div class="h-64 flex items-center justify-center">
            <canvas id="deptChart"></canvas>
        </div>
    </div>

</div>

{{-- ============================================= --}}
{{-- ÉVOLUTION + ACTIVITÉ RÉCENTE                  --}}
{{-- ============================================= --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

    {{-- Évolution effectif (LINE) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Évolution de l'effectif</h3>
        <p class="text-xs text-slate-500 mb-4">7 derniers mois</p>
        <div class="h-56">
            <canvas id="evolutionChart"></canvas>
        </div>
    </div>

    {{-- Activité récente --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Activité récente</h3>
        <div class="space-y-4">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-700">Demande de congé en attente</p>
                    <p class="text-xs text-slate-400">{{ now()->subDay()->format('Y-m-d') }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-700">Contrat expiré</p>
                    <p class="text-xs text-slate-400">{{ now()->subDays(2)->format('Y-m-d') }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-700">Nouvel entretien planifié</p>
                    <p class="text-xs text-slate-400">{{ now()->subDays(3)->format('Y-m-d') }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-violet-100 flex items-center justify-center shrink-0">
                    <span class="w-2 h-2 bg-violet-500 rounded-full"></span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-slate-700">Évaluation en cours</p>
                    <p class="text-xs text-slate-400">{{ now()->subDays(4)->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ============================================= --}}
{{-- TABLEAU : Congés en attente                    --}}
{{-- ============================================= --}}
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">

    <div class="flex items-center justify-between p-6 border-b border-slate-200">
        <h3 class="font-semibold text-slate-800">Congés en attente de validation</h3>
        <span class="text-xs font-medium text-amber-700 bg-amber-50 px-3 py-1 rounded-full">
            {{ $pendingConges }} en attente
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                <tr>
                    <th class="text-left px-6 py-3 font-semibold">Employé</th>
                    <th class="text-left px-6 py-3 font-semibold">Type</th>
                    <th class="text-left px-6 py-3 font-semibold">Période</th>
                    <th class="text-left px-6 py-3 font-semibold">Durée</th>
                    <th class="text-left px-6 py-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($congesEnAttente as $conge)
                    <tr class="hover:bg-purple-50/50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800">
                                {{ $conge->employee->prenom }} {{ $conge->employee->nom }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $conge->employee->matricule }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium bg-purple-100 text-purple-700 px-2 py-1 rounded">
                                {{ ucfirst($conge->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $conge->date_debut->format('Y-m-d') }} → {{ $conge->date_fin->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $conge->date_debut->diffInDays($conge->date_fin) + 1 }} jours
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button class="text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded transition">
                                    ✓ Approuver
                                </button>
                                <button class="text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded transition">
                                    ✕ Refuser
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            Aucun congé en attente
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection

{{-- ============================================= --}}
{{-- SCRIPTS : Chart.js + données PHP → JS          --}}
{{-- ⚠️ Ce bloc doit être EN DEHORS de @section     --}}
{{-- ============================================= --}}
@push('scripts')
<script>
    // =============================================
    // Récupérer les données passées par Laravel
    // =============================================
    const congesData    = @json($congesData);
    const deptLabels    = @json($deptLabels);
    const deptData      = @json($deptData);
    const deptColors    = @json($deptColors);
    const evolutionData = @json($evolutionData);

    const moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

    // =============================================
    // GRAPHIQUE 1 : Congés par mois (Bar)
    // =============================================
    const congesCtx = document.getElementById('congesChart').getContext('2d');
    
    new Chart(congesCtx, {
        type: 'bar',
        data: {
            labels: moisLabels,
            datasets: [{
                label: 'Congés',
                data: congesData,
                backgroundColor: '#7c3aed',
                borderRadius: 6,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e1b4b',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#7c3aed',
                    borderWidth: 1,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#64748b', font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11 } }
                }
            }
        }
    });

    // =============================================
    // GRAPHIQUE 2 : Répartition par département (Doughnut)
    // =============================================
    const deptCtx = document.getElementById('deptChart').getContext('2d');
    
    new Chart(deptCtx, {
        type: 'doughnut',
        data: {
            labels: deptLabels,
            datasets: [{
                data: deptData,
                backgroundColor: deptColors,
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 11 },
                        usePointStyle: true,
                        pointStyle: 'circle',
                    }
                },
                tooltip: {
                    backgroundColor: '#1e1b4b',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = ((value / total) * 100).toFixed(1);
                            return ` ${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // =============================================
    // GRAPHIQUE 3 : Évolution effectif (Line)
    // =============================================
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    
    // Créer un dégradé pour la ligne
    const gradient = evolutionCtx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(124, 58, 237, 0.25)');
    gradient.addColorStop(1, 'rgba(124, 58, 237, 0)');

    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: moisLabels.slice(0, 7), // Jan → Jul
            datasets: [{
                label: 'Effectif',
                data: evolutionData,
                borderColor: '#7c3aed',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#7c3aed',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e1b4b',
                    padding: 12,
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: { color: '#f1f5f9' },
                    ticks: { color: '#64748b', font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { size: 11 } }
                }
            }
        }
    });
</script>
@endpush
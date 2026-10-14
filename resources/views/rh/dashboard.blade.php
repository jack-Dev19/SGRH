@extends('layouts.dashboard')

@section('title', 'Tableau de bord RH')
<<<<<<< HEAD

@section('content')

    {{-- EN-TÊTE --}}
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Tableau de bord RH</h1>
            <p class="text-sm text-slate-500 mt-1">Vue d'ensemble de la gestion RH</p>
        </div>
    </div>

    {{-- CARTES STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Total employés</p>
            <p class="text-3xl font-extrabold text-slate-900 mb-1">{{ $stats['total_employes'] }}</p>
            <p class="text-xs text-slate-400">{{ $stats['employes_actifs'] }} actifs</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Employés actifs</p>
            <p class="text-3xl font-extrabold text-emerald-600 mb-1">{{ $stats['employes_actifs'] }}</p>
            <p class="text-xs text-slate-400">En poste actuellement</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Congés en cours</p>
            <p class="text-3xl font-extrabold text-blue-600 mb-1">{{ $stats['conges_en_cours'] }}</p>
            <p class="text-xs text-slate-400">{{ $stats['conges_en_attente'] }} en attente</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Masse salariale</p>
            <p class="text-2xl font-extrabold text-slate-900 mb-1">
                {{ number_format($masseSalariale, 0, ',', ' ') }}
            </p>
            <p class="text-xs text-slate-400">MGA / mois</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Recrutements</p>
            <p class="text-3xl font-extrabold text-purple-600 mb-1">{{ $stats['recrutements'] }}</p>
            <p class="text-xs text-slate-400">Offres publiées</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Notifications</p>
            <p class="text-3xl font-extrabold text-red-500 mb-1">{{ $notificationsNonLues }}</p>
            <p class="text-xs text-slate-400">Non lues</p>
        </div>

    </div>

    {{-- GRAPHIQUES --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Congés par mois --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Congés par mois</h2>
                    <p class="text-xs text-slate-500">Année {{ now()->year }}</p>
                </div>
            </div>

            <div class="h-64">
                <canvas id="chart-conges"
                        data-labels='@json(array_map(fn($m) => \Carbon\Carbon::create()->month($m)->locale("fr")->shortMonthName, array_keys($congesParMoisComplet)))'
                        data-values='@json(array_values($congesParMoisComplet))'>
                </canvas>
            </div>
        </div>

        {{-- Répartition départements --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Répartition par département</h2>
            <p class="text-xs text-slate-500 mb-4">
                Effectif total : {{ $stats['total_employes'] }}
            </p>

            <div class="h-48 mb-4">
                <canvas id="chart-departements"
                        data-labels='@json($repartitionDepartements->pluck("nom"))'
                        data-values='@json($repartitionDepartements->pluck("employes_count"))'>
                </canvas>
            </div>

            <div class="space-y-2 mt-4">
                @php
                    $palette = ['#3b82f6', '#ef4444', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#84cc16'];
                @endphp
                @foreach($repartitionDepartements as $i => $dep)
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $palette[$i % count($palette)] }}"></span>
                            <span class="text-sm text-slate-700">{{ $dep->nom }}</span>
                        </div>
                        <span class="text-sm font-bold text-slate-900">{{ $dep->employes_count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ÉVOLUTION + ACTIVITÉ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="mb-4">
                <h2 class="text-lg font-bold text-slate-900">Évolution de l'effectif</h2>
                <p class="text-xs text-slate-500">Cumul des embauches — {{ now()->year }}</p>
            </div>

            <div class="h-48">
                <canvas id="chart-effectif"
                        data-labels='@json(array_map(fn($m) => \Carbon\Carbon::create()->month($m)->locale("fr")->shortMonthName, array_keys($effectifParMois)))'
                        data-values='@json(array_values($effectifParMois))'>
                </canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Activité récente</h2>

            <div class="space-y-3">
                @forelse($activiteRecente as $item)
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-100 last:border-0 last:pb-0">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-2 shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900">{{ $item['titre'] }}</p>
                            <p class="text-xs text-slate-500">{{ $item['user'] }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $item['date']?->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucune activité récente.</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- CONGÉS EN ATTENTE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Congés en attente de validation</h2>
            <span class="text-xs font-semibold bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                {{ $stats['conges_en_attente'] }} en attente
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Employé</th>
                        <th class="px-6 py-3 text-left">Type</th>
                        <th class="px-6 py-3 text-left">Période</th>
                        <th class="px-6 py-3 text-left">Durée</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($congesEnAttente as $conge)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-3">
                                <div class="font-medium text-slate-900">
                                    {{ $conge->employe?->nom_complet ?? 'Employé inconnu' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $conge->employe?->matricule }}
                                </div>
                            </td>
                            <td class="px-6 py-3">
                                <span class="inline-block text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                    {{ $conge->typeConge?->nom ?? 'Type inconnu' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-slate-700">
                                {{ $conge->date_debut?->format('d/m/Y') }}
                                →
                                {{ $conge->date_fin?->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-3 text-slate-700">
                                {{ $conge->nombre_jours }} jour(s)
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex gap-2">
                                    <button class="text-xs font-medium px-3 py-1 rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100 transition">
                                        ✓ Approuver
                                    </button>
                                    <button class="text-xs font-medium px-3 py-1 rounded-full bg-red-50 text-red-700 hover:bg-red-100 transition">
                                        ✕ Refuser
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
                                Aucun congé en attente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

=======
@section('page-title', 'Tableau de bord RH')

@section('content')
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-2">Bienvenue dans votre espace RH</h2>
        <p class="text-slate-500">Gérez les employés, contrats, congés et recrutements.</p>
    </div>
>>>>>>> 9a241372d7253b6b18b4ea0b4d4bcc6ec0cbc7c2
@endsection
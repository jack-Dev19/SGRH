@extends('layouts.dashboard')

@section('title', 'Postes')
@section('page-title', 'Postes')

@section('content')

@php
    $postes = [
        ['id' => 1,  'code' => '#001', 'titre' => 'Développeur Full Stack',     'departement' => 'Informatique',        'employes' => 5, 'max' => 10],
        ['id' => 2,  'code' => '#002', 'titre' => 'Chef de Projet IT',           'departement' => 'Informatique',        'employes' => 2, 'max' => 10],
        ['id' => 3,  'code' => '#003', 'titre' => 'Comptable Senior',            'departement' => 'Comptabilité',        'employes' => 3, 'max' => 10],
        ['id' => 4,  'code' => '#004', 'titre' => 'Directeur Financier',         'departement' => 'Comptabilité',        'employes' => 1, 'max' => 10],
        ['id' => 5,  'code' => '#005', 'titre' => 'Responsable RH',              'departement' => 'Ressources Humaines', 'employes' => 1, 'max' => 10],
        ['id' => 6,  'code' => '#006', 'titre' => 'Assistant RH',                'departement' => 'Ressources Humaines', 'employes' => 2, 'max' => 10],
        ['id' => 7,  'code' => '#007', 'titre' => 'Chargé de Marketing Digital', 'departement' => 'Marketing',           'employes' => 3, 'max' => 10],
        ['id' => 8,  'code' => '#008', 'titre' => 'Commercial Terrain',          'departement' => 'Commercial',          'employes' => 8, 'max' => 10],
        ['id' => 9,  'code' => '#009', 'titre' => 'Directeur Général',           'departement' => 'Direction Générale',  'employes' => 1, 'max' => 10],
        ['id' => 10, 'code' => '#010', 'titre' => 'Développeur Mobile',          'departement' => 'Informatique',        'employes' => 2, 'max' => 10],
    ];

    $couleursDept = [
        'Informatique'        => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'bar' => 'bg-blue-500'],
        'Comptabilité'        => ['bg' => 'bg-violet-100',  'text' => 'text-violet-700',  'bar' => 'bg-violet-500'],
        'Ressources Humaines' => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'bar' => 'bg-red-500'],
        'Marketing'           => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'bar' => 'bg-emerald-500'],
        'Commercial'          => ['bg' => 'bg-orange-100',  'text' => 'text-orange-700',  'bar' => 'bg-orange-500'],
        'Direction Générale'  => ['bg' => 'bg-slate-100',   'text' => 'text-slate-700',   'bar' => 'bg-slate-500'],
    ];

    $departementsUniques = collect($postes)->pluck('departement')->unique();
@endphp

<div class="max-w-7xl mx-auto py-8">

    {{-- Fil d'ariane --}}
    <div class="inline-flex items-center gap-2 text-sm text-slate-500 mb-6">
        <span>Administration</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-medium text-purple-600">Postes</span>
    </div>

    {{-- En-tête --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Gestion des postes</h1>
            <p class="text-sm text-slate-500">Classification des emplois par département.</p>
        </div>

        <a href="{{ route('admin.postes.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau poste
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- BARRE DE RECHERCHE + FILTRES                   --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.postes.index') }}" class="flex flex-wrap items-center gap-3">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[280px]">
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un poste..."
                        class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                </div>
            </div>

            {{-- Filtre Département --}}
            <div class="relative min-w-[220px]" data-dropdown="departement">
                <input type="hidden" name="departement" id="input-departement" value="{{ request('departement') }}">

                <button type="button" id="dropdown-departement-btn"
                    class="w-full inline-flex items-center justify-between gap-2 pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium hover:border-purple-300 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span id="dropdown-departement-label" class="truncate text-left">
                        {{ request('departement') ?: 'Tous les départements' }}
                    </span>
                    <svg id="dropdown-departement-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div id="dropdown-departement-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 min-w-[240px] max-h-64 overflow-y-auto">
                    <button type="button" onclick="selectDropdown('departement', '', 'Tous les départements')"
                        class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        <span class="font-medium">Tous les départements</span>
                    </button>
                    @foreach($departementsUniques as $dept)
                        <button type="button" onclick="selectDropdown('departement', '{{ $dept }}', '{{ $dept }}')"
                            class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3 border-t border-slate-100">
                            <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span class="font-medium">{{ $dept }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Bouton Filtrer --}}
            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'departement']))
                <a href="{{ route('admin.postes.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Compteur --}}
    <p class="text-xs text-slate-500 mb-4 px-1">
        <span class="font-bold text-slate-700">{{ count($postes) }}</span> poste(s) enregistré(s)
    </p>

    {{-- ============================================= --}}
    {{-- TABLEAU                                        --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Code</th>
                        <th class="text-left px-6 py-3 font-semibold">Intitulé</th>
                        <th class="text-left px-6 py-3 font-semibold">Département</th>
                        <th class="text-left px-6 py-3 font-semibold">Employés</th>
                        <th class="text-right px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($postes as $poste)
                        @php
                            $c = $couleursDept[$poste['departement']] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'bar' => 'bg-slate-500'];
                            $pourcentage = $poste['max'] > 0 ? round(($poste['employes'] / $poste['max']) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-purple-50/40 transition">
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-slate-400">{{ $poste['code'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="font-semibold text-slate-800">{{ $poste['titre'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center text-xs font-semibold {{ $c['bg'] }} {{ $c['text'] }} px-3 py-1.5 rounded-full">
                                    {{ $poste['departement'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-24 h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $c['bar'] }} rounded-full transition-all duration-500" style="width: {{ $pourcentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $poste['employes'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.postes.edit', $poste['id']) }}"
                                        class="text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition">
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.postes.destroy', $poste['id']) }}" method="POST"
                                        onsubmit="return confirm('Supprimer ce poste ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition">
                                            Suppr.
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    window.selectDropdown = function(type, value, label) {
        document.getElementById('input-' + type).value = value;
        document.getElementById('dropdown-' + type + '-label').textContent = label;
        document.getElementById('dropdown-' + type + '-menu').classList.add('hidden');
        document.getElementById('dropdown-' + type + '-chevron').style.transform = 'rotate(0deg)';
    };

    window.toggleDropdown = function(type) {
        const menu = document.getElementById('dropdown-' + type + '-menu');
        const chevron = document.getElementById('dropdown-' + type + '-chevron');
        const isHidden = menu.classList.contains('hidden');

        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');

        if (isHidden) {
            menu.classList.remove('hidden');
            chevron.style.transform = 'rotate(180deg)';
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const deptBtn = document.getElementById('dropdown-departement-btn');
        if (deptBtn) {
            deptBtn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown('departement'); });
        }
        document.addEventListener('click', () => {
            document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
        });
        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(menu => {
            menu.addEventListener('click', e => e.stopPropagation());
        });
    });
</script>
@endpush
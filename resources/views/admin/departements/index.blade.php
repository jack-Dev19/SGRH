@extends('layouts.dashboard')

@section('title', 'Départements')
@section('page-title', 'Départements')

@section('content')

@php
    $departements = [
        ['id' => 1, 'nom' => 'Informatique',        'description' => 'Développement et support technique', 'chef' => 'Mehdi Larbi',    'effectif' => 12, 'capacite' => 20, 'couleur' => 'blue'],
        ['id' => 2, 'nom' => 'Comptabilité',        'description' => 'Gestion financière et comptable',    'chef' => 'Amira Bensalem', 'effectif' => 8,  'capacite' => 12, 'couleur' => 'violet'],
        ['id' => 3, 'nom' => 'Ressources Humaines', 'description' => 'Gestion du personnel et recrutement', 'chef' => 'Nadia Tazi',     'effectif' => 6,  'capacite' => 10, 'couleur' => 'red'],
        ['id' => 4, 'nom' => 'Marketing',           'description' => 'Communication et stratégie',          'chef' => 'Karim Benali',   'effectif' => 9,  'capacite' => 15, 'couleur' => 'emerald'],
        ['id' => 5, 'nom' => 'Commercial',          'description' => 'Ventes et relation client',           'chef' => 'Sara Oukili',    'effectif' => 14, 'capacite' => 20, 'couleur' => 'orange'],
        ['id' => 6, 'nom' => 'Direction Générale',  'description' => 'Pilotage stratégique',                'chef' => 'Ahmed Mansouri', 'effectif' => 3,  'capacite' => 5,  'couleur' => 'slate'],
    ];

    $couleursMap = [
        'blue'    => ['bg' => 'bg-blue-500',    'light' => 'bg-blue-100',    'text' => 'text-blue-700',    'gradient' => 'from-blue-500 to-blue-600'],
        'violet'  => ['bg' => 'bg-violet-500',  'light' => 'bg-violet-100',  'text' => 'text-violet-700',  'gradient' => 'from-violet-500 to-violet-600'],
        'red'     => ['bg' => 'bg-red-500',     'light' => 'bg-red-100',     'text' => 'text-red-700',     'gradient' => 'from-red-500 to-red-600'],
        'emerald' => ['bg' => 'bg-emerald-500', 'light' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'gradient' => 'from-emerald-500 to-emerald-600'],
        'orange'  => ['bg' => 'bg-orange-500',  'light' => 'bg-orange-100',  'text' => 'text-orange-700',  'gradient' => 'from-orange-500 to-orange-600'],
        'slate'   => ['bg' => 'bg-slate-500',   'light' => 'bg-slate-100',   'text' => 'text-slate-700',   'gradient' => 'from-slate-600 to-slate-700'],
    ];

    $totalDepts    = count($departements);
    $totalEmployes = array_sum(array_column($departements, 'effectif'));
    $totalCapacite = array_sum(array_column($departements, 'capacite'));
    $plusGrand     = collect($departements)->sortByDesc('effectif')->first();
@endphp

<div class="max-w-6xl mx-auto py-8">

    {{-- Fil d'ariane --}}
    <div class="inline-flex items-center gap-2 text-sm text-slate-500 mb-6">
        <span>Administration</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-medium text-purple-600">Départements</span>
    </div>

    {{-- En-tête --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Gestion des départements</h1>
            <p class="text-sm text-slate-500">Organisez votre entreprise en départements et gérez leurs responsables.</p>
        </div>

        <a href="{{ route('admin.departements.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau département
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- BARRE DE RECHERCHE + FILTRES                   --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('admin.departements.index') }}" class="flex flex-wrap items-center gap-3">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[280px]">
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un département..."
                        class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                </div>
            </div>

            {{-- Filtre Chef --}}
            <div class="relative min-w-[220px]" data-dropdown="chef">
                <input type="hidden" name="chef" id="input-chef" value="{{ request('chef') }}">

                <button type="button" id="dropdown-chef-btn"
                    class="w-full inline-flex items-center justify-between gap-2 pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium hover:border-purple-300 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span id="dropdown-chef-label" class="truncate text-left">
                        {{ request('chef') ?: 'Tous les chefs' }}
                    </span>
                    <svg id="dropdown-chef-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div id="dropdown-chef-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 min-w-[240px] max-h-64 overflow-y-auto">
                    <button type="button" onclick="selectDropdown('chef', '', 'Tous les chefs')"
                        class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        </div>
                        <span class="font-medium">Tous les chefs</span>
                    </button>
                    @foreach(collect($departements)->pluck('chef')->unique() as $chef)
                        <button type="button" onclick="selectDropdown('chef', '{{ $chef }}', '{{ $chef }}')"
                            class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3 border-t border-slate-100">
                            <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-[10px] shrink-0">
                                {{ strtoupper(substr($chef, 0, 1)) }}
                            </div>
                            <span class="font-medium">{{ $chef }}</span>
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

            @if(request()->hasAny(['search', 'chef']))
                <a href="{{ route('admin.departements.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Compteur --}}
    <p class="text-xs text-slate-500 mb-4 px-1">
        <span class="font-bold text-slate-700">{{ count($departements) }}</span> département(s)
    </p>

    {{-- ============================================= --}}
    {{-- GRILLE DE CARDS                                --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @foreach($departements as $dept)
            @php
                $c = $couleursMap[$dept['couleur']] ?? $couleursMap['slate'];
                $initiales = strtoupper(substr($dept['chef'], 0, 1) . substr(explode(' ', $dept['chef'])[1] ?? '', 0, 1));
                $pourcentage = $dept['capacite'] > 0 ? round(($dept['effectif'] / $dept['capacite']) * 100) : 0;
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-purple-200 hover:-translate-y-1 transition-all duration-300">
                <div class="relative h-24 bg-gradient-to-br {{ $c['gradient'] }} overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            <span class="w-1 h-1 rounded-full bg-emerald-300 animate-pulse"></span>
                            Actif
                        </span>
                    </div>
                </div>

                <div class="relative px-4 pb-4">
                    <div class="flex justify-center -mt-10 mb-3">
                        <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg ring-1 ring-slate-100">
                            <div class="w-full h-full rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-xl">
                                {{ $initiales }}
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-3">
                        <h3 class="font-bold text-slate-900 text-sm leading-tight mb-1">{{ $dept['nom'] }}</h3>
                        <p class="text-[10px] text-slate-500 leading-tight line-clamp-2">{{ $dept['description'] }}</p>
                    </div>

                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold {{ $c['light'] }} {{ $c['text'] }} px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full {{ $c['bg'] }}"></span>
                            Chef : {{ $dept['chef'] }}
                        </span>
                    </div>

                    <div class="mb-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Effectif</span>
                            <span class="text-xs font-bold text-slate-700">{{ $dept['effectif'] }} <span class="text-slate-400 font-normal">/ {{ $dept['capacite'] }}</span></span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $c['bg'] }} rounded-full transition-all duration-700" style="width: {{ $pourcentage }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5">{{ $pourcentage }}% de la capacité utilisée</p>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.departements.edit', $dept['id']) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>
                        <form action="{{ route('admin.departements.destroy', $dept['id']) }}" method="POST"
                            onsubmit="return confirm('Supprimer ce département ?');" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-red-700 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Suppr.
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

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
        const chefBtn = document.getElementById('dropdown-chef-btn');
        if (chefBtn) {
            chefBtn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown('chef'); });
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
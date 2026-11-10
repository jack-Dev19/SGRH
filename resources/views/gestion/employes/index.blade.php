@extends('layouts.dashboard')

@section('title', 'Employés')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    deptOpen: false,
    statutOpen: false,
    selectedDept: '{{ request('departement') }}',
    selectedStatut: '{{ request('statut') }}',
    departementsList: {{ Js::from($departements->map(fn($d) => ['id' => (string)$d->id, 'nom' => $d->nom])->values()) }},
    statutsList: [
        { id: 'actif', nom: 'Actif' },
        { id: 'inactif', nom: 'Inactif' },
        { id: 'sorti', nom: 'Sorti' }
    ],
    get selectedDeptName() {
        if (!this.selectedDept) return '';
        const d = this.departementsList.find(d => d.id == this.selectedDept);
        return d ? d.nom : '';
    }
}">

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    Personnel
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Employés</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $employes->total() }} employé(s) enregistré(s)</p>
        </div>

        <a href="{{ route('gestion.employes.create') }}"
            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvel employé
        </a>
    </div>

    {{-- Barre de recherche + filtres --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.employes.index') }}" class="flex flex-wrap items-center gap-2">

            <input type="hidden" name="departement" x-model="selectedDept">
            <input type="hidden" name="statut" x-model="selectedStatut">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[280px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, matricule, email…"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- ⭐ FILTRE DÉPARTEMENT DROPDOWN PRO ⭐ --}}
            <div class="min-w-[220px] relative" @click.away="deptOpen = false">
                <button type="button" @click="deptOpen = !deptOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="selectedDeptName || 'Tous les départements'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="deptOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="deptOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par département</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <li @click="selectedDept = ''; deptOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="!selectedDept ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                            <span class="text-sm font-medium">Tous les départements</span>
                            <div :class="!selectedDept ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                <svg x-show="!selectedDept" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </li>
                        <template x-for="dept in departementsList" :key="dept.id">
                            <li @click="selectedDept = dept.id; deptOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedDept == dept.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="dept.nom"></span>
                                <div :class="selectedDept == dept.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedDept == dept.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- ⭐ FILTRE STATUT DROPDOWN PRO ⭐ --}}
            <div class="min-w-[190px] relative" @click.away="statutOpen = false">
                <button type="button" @click="statutOpen = !statutOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="selectedStatut ? statutsList.find(s => s.id === selectedStatut)?.nom : 'Tous les statuts'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="statutOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="statutOpen" x-transition class="absolute z-40 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par statut</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <li @click="selectedStatut = ''; statutOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="!selectedStatut ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                            <span class="text-sm font-medium">Tous les statuts</span>
                            <div :class="!selectedStatut ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                <svg x-show="!selectedStatut" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </li>
                        <template x-for="opt in statutsList" :key="opt.id">
                            <li @click="selectedStatut = opt.id; statutOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedStatut === opt.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="opt.nom"></span>
                                <div :class="selectedStatut === opt.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedStatut === opt.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- Bouton Filtrer --}}
            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'departement', 'statut']))
                <a href="{{ route('gestion.employes.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Grille de cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

        @forelse($employes as $emp)
            @php
                $palette = [
                    ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'gradient' => 'from-blue-500 to-blue-600',       'dot' => 'bg-blue-500'],
                    ['bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'gradient' => 'from-violet-500 to-violet-600',   'dot' => 'bg-violet-500'],
                    ['bg' => 'bg-red-50',     'text' => 'text-red-700',     'gradient' => 'from-red-500 to-red-600',         'dot' => 'bg-red-500'],
                    ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'gradient' => 'from-emerald-500 to-emerald-600', 'dot' => 'bg-emerald-500'],
                    ['bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'gradient' => 'from-orange-500 to-orange-600',   'dot' => 'bg-orange-500'],
                    ['bg' => 'bg-slate-50',   'text' => 'text-slate-700',   'gradient' => 'from-slate-600 to-slate-700',     'dot' => 'bg-slate-500'],
                ];

                $idx = crc32($emp->id) % count($palette);
                $c = $palette[$idx];

                $pointagesCount = \App\Models\Pointage::where('employe_id', $emp->id)->count();
                $documentsCount = \Schema::hasTable('documents')
                    ? \DB::table('documents')->where('employe_id', $emp->id)->count()
                    : 0;
                $contratsCount = \Schema::hasTable('contrats')
                    ? \DB::table('contrats')->where('employe_id', $emp->id)->count()
                    : 0;
                $absencesCount = \Schema::hasTable('absences')
                    ? \DB::table('absences')->where('employe_id', $emp->id)->count()
                    : 0;
                $presencesCount = \Schema::hasTable('presences')
                    ? \DB::table('presences')->where('employe_id', $emp->id)->count()
                    : 0;
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">
                
                {{-- Bandeau gradient --}}
                <div class="relative h-20 bg-gradient-to-br {{ $c['gradient'] }} overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-white/10 rounded-full"></div>
                    
                    {{-- Badge statut --}}
                    <div class="absolute top-2.5 right-2.5">
                        @if($emp->statut === 'actif')
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                Actif
                            </span>
                        @elseif($emp->statut === 'inactif')
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                Inactif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                Sorti
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Avatar --}}
                <div class="relative px-4 pb-4 flex-1 flex flex-col">
                    <div class="flex justify-center -mt-10 mb-3">
                        <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg ring-1 ring-slate-100">
                            <div class="w-full h-full rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-xl">
                                {{ $emp->initiales }}
                            </div>
                        </div>
                    </div>

                    {{-- Nom + matricule --}}
                    <div class="text-center mb-3">
                        <h3 class="font-bold text-slate-900 text-sm leading-tight mb-1 truncate">{{ $emp->nom_complet }}</h3>
                        <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase font-mono">{{ $emp->matricule }}</p>
                    </div>

                    {{-- Département --}}
                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center gap-1.5 text-[10px] font-semibold {{ $c['bg'] }} {{ $c['text'] }} px-3 py-1 rounded-full ring-1 ring-inset ring-white/50">
                            <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                            {{ $emp->departement?->nom ?? 'Sans département' }}
                        </span>
                    </div>

                    {{-- Infos --}}
                    <div class="space-y-2 mb-4 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[11px] text-slate-600 truncate">{{ $emp->email ?? '—' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-700 truncate">{{ $emp->poste?->nom ?? 'Poste non défini' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <span class="text-[11px] text-slate-600">Embauché le {{ $emp->date_embauche?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 mt-auto">
                        <a href="{{ route('gestion.employes.show', $emp) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Voir
                        </a>
                        <a href="{{ route('gestion.employes.edit', $emp) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>
                        <form action="{{ route('gestion.employes.destroy', $emp) }}" method="POST"
                            data-delete-employe
                            data-employe-nom="{{ $emp->nom_complet }}"
                            data-pointages="{{ $pointagesCount }}"
                            data-documents="{{ $documentsCount }}"
                            data-contrats="{{ $contratsCount }}"
                            data-absences="{{ $absencesCount }}"
                            data-presences="{{ $presencesCount }}"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition ring-1 ring-inset ring-red-200"
                                title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Aucun employé trouvé</h3>
                <p class="mt-1 text-sm text-slate-500">
                    @if(request()->hasAny(['search', 'departement', 'statut']))
                        Aucun résultat ne correspond à vos filtres. Essayez d'autres critères.
                    @else
                        Commencez par ajouter un nouvel employé.
                    @endif
                </p>
                <div class="mt-5">
                    @if(request()->hasAny(['search', 'departement', 'statut']))
                        <a href="{{ route('gestion.employes.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                            Effacer les filtres
                        </a>
                    @else
                        <a href="{{ route('gestion.employes.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Nouvel employé
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($employes->hasPages())
        <div class="mt-6">{{ $employes->links() }}</div>
    @endif
</div>

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-delete-employe]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const nomEmploye = form.dataset.employeNom || 'cet employé';
            const pointages  = parseInt(form.dataset.pointages || 0);
            const documents  = parseInt(form.dataset.documents || 0);
            const contrats   = parseInt(form.dataset.contrats || 0);
            const absences   = parseInt(form.dataset.absences || 0);
            const presences  = parseInt(form.dataset.presences || 0);

            const dependances = [];
            if (pointages > 0) dependances.push(`📌 ${pointages} pointage(s)`);
            if (documents > 0) dependances.push(`📄 ${documents} document(s)`);
            if (contrats > 0)  dependances.push(`📝 ${contrats} contrat(s)`);
            if (absences > 0)  dependances.push(`⚠️ ${absences} absence(s)`);
            if (presences > 0) dependances.push(`✅ ${presences} présence(s)`);

            if (dependances.length > 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Suppression impossible',
                    html: `
                        <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                            <p style="margin-bottom: 10px;">
                                L'employé <strong>${nomEmploye}</strong> ne peut pas être supprimé car il possède des enregistrements associés :
                            </p>
                            <ul style="margin: 10px 0 10px 20px; color: #dc2626; font-weight: 600;">
                                ${dependances.map(d => `<li>${d}</li>`).join('')}
                            </ul>
                            <p style="margin-top: 10px; color: #475569;">
                                Pour préserver l'historique, veuillez <strong>archiver</strong> ou <strong>désactiver</strong> cet employé plutôt que de le supprimer définitivement.
                            </p>
                        </div>
                    `,
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Compris',
                    showCloseButton: true,
                    allowOutsideClick: true,
                });
                return;
            }

            Swal.fire({
                icon: 'warning',
                title: 'Confirmer la suppression',
                html: `Voulez-vous vraiment supprimer <strong>${nomEmploye}</strong> ?<br><br>
                       <span style="color: #dc2626; font-size: 12px;">⚠️ Cette action est irréversible.</span>`,
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
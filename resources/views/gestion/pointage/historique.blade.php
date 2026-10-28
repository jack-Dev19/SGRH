@extends('layouts.dashboard')

@section('title', 'Historique des pointages')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    employeOpen: false,
    typeOpen: false,
    selectedEmploye: '{{ request('employe_id') }}',
    selectedType: '{{ request('type') }}',
    employesList: {{ Js::from($employes->map(fn($e) => ['id' => (string)$e->id, 'nom' => $e->nom_complet])->values()) }},
    typesList: [
        { id: '', nom: 'Tous les types' },
        { id: 'entree', nom: 'Arrivées' },
        { id: 'sortie', nom: 'Départs' }
    ]
}">

    {{-- Messages Flash --}}
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
                    Pointage
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Historique des pointages</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $pointages->total() }} enregistrement(s) au total</p>
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            <a href="{{ route('gestion.pointage.anomalies') }}"
                class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-orange-50 hover:bg-orange-100 text-orange-700 text-sm font-bold px-5 py-3 rounded-xl transition-colors ring-1 ring-inset ring-orange-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Anomalies
            </a>

            <a href="{{ route('gestion.pointage.scanner') }}"
                class="group flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold px-5 py-3 rounded-xl transition-all hover:-translate-y-0.5 shadow-lg shadow-slate-900/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Scanner
            </a>
        </div>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Arrivées</p>
            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $stats['arrivees'] }}</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Départs</p>
            <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ $stats['departs'] }}</p>
        </div>
        <div class="rounded-2xl border border-orange-200 bg-orange-50/50 p-5 shadow-sm">
            <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">Anomalies</p>
            <p class="text-2xl font-extrabold text-orange-700 mt-1">{{ $stats['anomalies'] }}</p>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.pointage.historique') }}" class="flex flex-wrap items-center gap-2">
            
            <input type="hidden" name="employe_id" x-model="selectedEmploye">
            <input type="hidden" name="type" x-model="selectedType">

            {{-- Date --}}
            <div class="min-w-[180px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <input type="date" name="date" value="{{ $date }}"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- ⭐ EMPLOYÉ DROPDOWN PRO ⭐ --}}
            <div class="min-w-[220px] relative" @click.away="employeOpen = false">
                <button type="button" @click="employeOpen = !employeOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="employesList.find(e => e.id === selectedEmploye)?.nom || 'Tous les employés'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="employeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="employeOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par employé</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <li @click="selectedEmploye = ''; employeOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="!selectedEmploye ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                            <span class="text-sm font-medium">Tous les employés</span>
                            <div :class="!selectedEmploye ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                <svg x-show="!selectedEmploye" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </li>
                        <template x-for="e in employesList" :key="e.id">
                            <li @click="selectedEmploye = e.id; employeOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedEmploye === e.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="e.nom"></span>
                                <div :class="selectedEmploye === e.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedEmploye === e.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- ⭐ TYPE DROPDOWN PRO ⭐ --}}
            <div class="min-w-[180px] relative" @click.away="typeOpen = false">
                <button type="button" @click="typeOpen = !typeOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="typesList.find(t => t.id === selectedType)?.nom || 'Tous les types'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="typeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="typeOpen" x-transition class="absolute z-40 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par type</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <template x-for="t in typesList" :key="t.id">
                            <li @click="selectedType = t.id; typeOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedType === t.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="t.nom"></span>
                                <div :class="selectedType === t.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedType === t.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- Toggle anomalies --}}
            <label class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 rounded-lg ring-1 ring-inset ring-slate-200 cursor-pointer hover:bg-slate-100 transition-colors">
                <input type="checkbox" name="anomalies" value="1" {{ request('anomalies') ? 'checked' : '' }} class="h-4 w-4 text-purple-600 rounded focus:ring-2 focus:ring-purple-500/20">
                <span class="text-sm font-medium text-slate-700">Anomalies seulement</span>
            </label>

            {{-- Bouton Filtrer --}}
            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                Filtrer
            </button>

            @if(request()->hasAny(['date', 'employe_id', 'type', 'anomalies']))
                <a href="{{ route('gestion.pointage.historique') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Employé</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Date</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Heure</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Source</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Statut</th>
                        <th class="text-right px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pointages as $p)
                        <tr class="group hover:bg-slate-50/80 transition-colors {{ $p->est_anomalie ? 'bg-orange-50/30' : '' }}">
                            {{-- Employé --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                        {{ strtoupper(substr($p->employe?->prenom ?? '??', 0, 1) . substr($p->employe?->nom ?? '', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ $p->employe?->nom_complet ?? '—' }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $p->employe?->matricule }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Date --}}
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-xs font-medium text-slate-700">{{ $p->date_heure?->format('d/m/Y') }}</p>
                            </td>

                            {{-- Heure --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-mono font-bold text-slate-800 ring-1 ring-inset ring-slate-200">
                                    {{ $p->date_heure?->format('H:i:s') }}
                                </span>
                            </td>

                            {{-- Type --}}
                            <td class="px-6 py-4 text-center">
                                @if($p->type === 'entree')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                        Arrivée
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700 ring-1 ring-inset ring-blue-200">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        Départ
                                    </span>
                                @endif
                            </td>

                            {{-- Source --}}
                            <td class="px-6 py-4 text-center hidden lg:table-cell">
                                @if($p->type_scan === 'qr_code')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-700 ring-1 ring-inset ring-purple-200">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        QR Code
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold text-slate-600 ring-1 ring-inset ring-slate-200">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Manuel
                                    </span>
                                @endif
                            </td>

                            {{-- Statut --}}
                            <td class="px-6 py-4 text-center">
                                @if($p->est_anomalie)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-[10px] font-bold text-orange-700 shadow-sm ring-1 ring-inset ring-orange-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                                        Anomalie
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-[10px] font-bold text-emerald-700 shadow-sm ring-1 ring-inset ring-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Valide
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('gestion.pointage.corriger', $p->id) }}"
                                    class="inline-flex items-center gap-1.5 text-[10px] font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 px-3 py-1.5 rounded-lg transition-colors ring-1 ring-inset ring-purple-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Corriger
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-900 mb-1">Aucun pointage trouvé</h3>
                                <p class="text-sm text-slate-500">
                                    @if(request()->hasAny(['date', 'employe_id', 'type', 'anomalies']))
                                        Aucun résultat pour cette période. Essayez d'autres filtres.
                                    @else
                                        Aucun pointage enregistré pour cette journée.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pointages->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">{{ $pointages->links() }}</div>
        @endif
    </div>

</div>

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>

@endsection
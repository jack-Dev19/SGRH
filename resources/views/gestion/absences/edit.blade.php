@extends('layouts.dashboard')

@section('title', 'Modifier absence')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8" x-data="{
    employeOpen: false,
    selectedEmploye: '{{ old('employe_id', $absence->employe_id) }}',
    selectedStatut: '{{ old('statut', $absence->statut) }}',
    employesList: {{ Js::from($employes->map(fn($e) => ['id' => (string)$e->id, 'nom' => $e->nom_complet . ' (' . $e->matricule . ')'])->values()) }},
    statutsList: [
        { id: 'en_attente', nom: 'En attente', color: 'yellow' },
        { id: 'justifie', nom: 'Justifiée', color: 'emerald' },
        { id: 'non_justifie', nom: 'Non justifiée', color: 'red' },
        { id: 'autorise', nom: 'Autorisée', color: 'blue' },
        { id: 'annule', nom: 'Annulée', color: 'slate' }
    ],
    get selectedEmployeName() {
        if (!this.selectedEmploye) return '';
        const e = this.employesList.find(e => e.id == this.selectedEmploye);
        return e ? e.nom : '';
    },
    getColorClass(color) {
        const classes = {
            yellow: 'border-yellow-500 bg-yellow-50',
            emerald: 'border-emerald-500 bg-emerald-50',
            red: 'border-red-500 bg-red-50',
            blue: 'border-blue-500 bg-blue-50',
            slate: 'border-slate-500 bg-slate-50'
        };
        return classes[color] || 'border-slate-200 bg-slate-50';
    }
}">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2 flex-wrap">
        <a href="{{ route('gestion.absences.index') }}" class="hover:text-purple-700 transition-colors">Absences</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('gestion.absences.show', $absence) }}" class="hover:text-purple-700 transition-colors truncate max-w-[120px]">{{ $absence->employe?->nom_complet }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold">Modifier</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">
        
        {{-- En-tête --}}
        <div class="px-8 pt-10 pb-6 text-center">
            <h1 class="text-3xl font-extrabold text-purple-700 tracking-wide uppercase">Modifier</h1>
            <p class="text-sm font-semibold text-slate-400 tracking-widest uppercase mt-1">L'absence</p>
            <p class="text-xs text-purple-500 mt-3 bg-purple-50 inline-block px-3 py-1 rounded-full border border-purple-100">
                {{ $absence->employe?->nom_complet }} · {{ $absence->date_debut?->format('d/m/Y') }}
            </p>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('gestion.absences.update', $absence) }}" method="POST" enctype="multipart/form-data" class="px-8 pb-10 relative z-10">
            @csrf
            @method('PUT')

            {{-- Champs cachés pour la soumission --}}
            <input type="hidden" name="employe_id" x-model="selectedEmploye">
            <input type="hidden" name="statut" x-model="selectedStatut">

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-100 text-red-600 rounded-2xl p-4 text-sm">
                    <p class="font-bold mb-1">Erreurs :</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                
                {{-- SECTION 1 : EMPLOYÉ & PÉRIODE --}}
                <div>
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">1</span>
                        Employé & Période
                    </h2>

                    <div class="space-y-3">
                        
                        {{-- ⭐ EMPLOYÉ DROPDOWN PRO ⭐ --}}
                        <div class="relative" @click.away="employeOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            
                            <button type="button" @click="employeOpen = !employeOpen" 
                                class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="selectedEmployeName || '— Sélectionner un employé * —'" :class="!selectedEmployeName ? 'text-purple-300' : 'font-medium'"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="employeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>

                            <div x-show="employeOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50"><p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Employés</p></div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="emp in employesList" :key="emp.id">
                                        <li @click="selectedEmploye = emp.id; employeOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="selectedEmploye == emp.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="emp.nom"></span>
                                            <div :class="selectedEmploye == emp.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedEmploye == emp.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Dates --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="date" name="date_debut" required value="{{ old('date_debut', $absence->date_debut?->format('Y-m-d')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="date" name="date_fin" required value="{{ old('date_fin', $absence->date_fin?->format('Y-m-d')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2 : DÉTAILS --}}
                <div class="pt-2">
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">2</span>
                        Détails
                    </h2>

                    <div class="space-y-3">
                        {{-- Motif --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <input type="text" name="motif" required maxlength="150" value="{{ old('motif', $absence->motif) }}" placeholder="Motif (ex: Maladie, Mission…) *"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>

                        {{-- Description --}}
                        <div class="relative">
                            <div class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <textarea name="description" rows="3" maxlength="500" placeholder="Description détaillée (optionnel)…"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-3xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none resize-none">{{ old('description', $absence->description) }}</textarea>
                        </div>

                        {{-- Justificatif actuel --}}
                        @if($absence->justificatif)
                            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4">
                                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Justificatif actuel</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <a href="{{ Storage::url($absence->justificatif) }}" target="_blank" class="text-sm text-emerald-700 hover:underline font-bold">
                                        Voir le justificatif actuel
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Nouveau justificatif --}}
                        <div class="relative">
                            <input type="file" name="justificatif" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-3.5 file:px-5 file:rounded-full file:border-0 file:bg-purple-600 file:text-white file:font-bold file:text-xs file:cursor-pointer hover:file:bg-purple-700 border-2 border-purple-200 rounded-full bg-white transition-all cursor-pointer focus:outline-none focus:ring-4 focus:ring-purple-500/20">
                            <p class="text-[11px] text-purple-400 pl-4 mt-1">
                                Formats : PDF, JPG, PNG (max 2 Mo).
                                @if($absence->justificatif)
                                    Laisser vide pour conserver l'actuel.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3 : STATUT --}}
                <div class="pt-2">
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">3</span>
                        Statut
                    </h2>

                    <div class="space-y-2">
                        @php
                            $statuts = [
                                'en_attente'   => ['label' => 'En attente',    'color' => 'yellow'],
                                'justifie'     => ['label' => 'Justifiée',     'color' => 'emerald'],
                                'non_justifie' => ['label' => 'Non justifiée', 'color' => 'red'],
                                'autorise'     => ['label' => 'Autorisée',     'color' => 'blue'],
                                'annule'       => ['label' => 'Annulée',       'color' => 'slate'],
                            ];
                            $colors = [
                                'yellow'  => ['bg' => 'bg-yellow-50',  'border' => 'border-yellow-200',  'text' => 'text-yellow-800',  'sub' => 'text-yellow-600'],
                                'emerald' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'sub' => 'text-emerald-600'],
                                'red'     => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'text' => 'text-red-800',     'sub' => 'text-red-600'],
                                'blue'    => ['bg' => 'bg-blue-50',    'border' => 'border-blue-200',    'text' => 'text-blue-800',    'sub' => 'text-blue-600'],
                                'slate'   => ['bg' => 'bg-slate-50',   'border' => 'border-slate-200',   'text' => 'text-slate-800',   'sub' => 'text-slate-600'],
                            ];
                        @endphp

                        @foreach($statuts as $key => $statut)
                            @php $cc = $colors[$statut['color']]; @endphp
                            <label class="block {{ $cc['bg'] }} border-2 rounded-2xl p-4 cursor-pointer transition-all"
                                :class="selectedStatut === '{{ $key }}' ? '{{ $cc['border'] }} ring-2 ring-offset-1' : 'border-slate-200'"
                                @click="selectedStatut = '{{ $key }}'">
                                <div class="flex items-start gap-3">
                                    <input type="radio" name="statut" value="{{ $key }}"
                                        {{ old('statut', $absence->statut) === $key ? 'checked' : '' }} class="mt-1">
                                    <div>
                                        <p class="font-bold {{ $cc['text'] }}">{{ $statut['label'] }}</p>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-6 pb-2 space-y-3">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold py-4 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5 outline-none focus:ring-4 focus:ring-purple-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        ENREGISTRER LES MODIFICATIONS
                    </button>

                    <div class="flex items-center justify-between px-4 pt-2 border-t border-purple-100/50">
                        <a href="{{ route('gestion.absences.show', $absence) }}" class="text-xs font-bold text-purple-400 hover:text-purple-600 py-2 transition-colors">
                            Annuler
                        </a>
                        <button type="button"
                            onclick="if(confirm('⚠️ Supprimer définitivement cette absence ?')) { document.getElementById('delete-form').submit(); }"
                            class="text-xs font-bold text-red-400 hover:text-red-600 py-2 transition-colors">
                            Supprimer l'absence
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Vagues décoratives --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none overflow-hidden h-40">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full">
                <path fill="rgba(167, 139, 250, 0.4)" d="M0,100 C150,150 350,50 500,100 L500,150 L0,150 Z"></path>
                <path fill="rgba(139, 92, 246, 0.8)" d="M0,120 C150,170 350,70 500,120 L500,150 L0,150 Z"></path>
                <path fill="#7C3AED" d="M0,140 C150,190 350,90 500,140 L500,150 L0,150 Z"></path>
            </svg>
        </div>
    </div>

    {{-- Formulaire de suppression caché --}}
    <form id="delete-form" action="{{ route('gestion.absences.destroy', $absence) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d8b4fe; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #a855f7; }
</style>

@endsection
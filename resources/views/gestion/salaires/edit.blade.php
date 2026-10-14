@extends('layouts.dashboard')

@section('title', 'Modifier salaire')

@section('content')

<div class="max-w-6xl mx-auto py-4">

    <a href="{{ route('gestion.salaires.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux salaires
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        {{-- CARTE ILLUSTRATION --}}
        <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 rounded-[2rem] shadow-2xl shadow-blue-500/30 overflow-hidden min-h-[640px] flex flex-col lg:sticky lg:top-24">
            <div class="relative z-10 px-10 pt-12 text-center">
                <h2 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">Modifier</h2>
                <p class="text-sm text-blue-100 uppercase tracking-widest">Le salaire</p>
            </div>

            <div class="relative z-10 flex-1 px-10 py-12 flex items-center justify-center">
                <div class="text-center w-full">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-lg mb-2">
                        {{ $salaire->employe?->nom_complet ?? 'Employé' }}
                    </p>
                    <p class="text-blue-100 text-sm">
                        {{ number_format($salaire->salaire_base, 0, ',', ' ') }} MGA
                    </p>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#dbeafe" opacity="0.3"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#a5b4fc" opacity="0.5"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#818cf8" opacity="0.7"/></svg>
            </div>
        </div>

        {{-- FORMULAIRE --}}
        <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-blue-500/20 overflow-hidden min-h-[640px] flex flex-col">
            <form action="{{ route('gestion.salaires.update', $salaire) }}" method="POST" class="relative z-10 px-8 pt-10 pb-32 flex-1">
                @csrf
                @method('PUT')

                <div class="text-center mb-6">
                    <h2 class="text-3xl font-extrabold text-blue-700 uppercase tracking-wider mb-2">Modifier</h2>
                    <p class="text-sm text-blue-400 uppercase tracking-widest">Le salaire</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-2xl mb-5">
                        @foreach ($errors->all() as $error) <p>• {{ $error }}</p> @endforeach
                    </div>
                @endif

                <div class="space-y-3">

                    {{-- Employé --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <select name="employe_id" required
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition appearance-none">
                            @foreach($employes as $emp)
                                <option value="{{ $emp->id }}" {{ old('employe_id', $salaire->employe_id) === $emp->id ? 'selected' : '' }}>
                                    {{ $emp->nom_complet }} ({{ $emp->matricule }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Salaire de base --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <input type="number" name="salaire_base" min="0" step="1000" required value="{{ old('salaire_base', $salaire->salaire_base) }}"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="date" name="date_debut" required value="{{ old('date_debut', $salaire->date_debut?->format('Y-m-d')) }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="date" name="date_fin" value="{{ old('date_fin', $salaire->date_fin?->format('Y-m-d')) }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Motif --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <input type="text" name="motif" value="{{ old('motif', $salaire->motif) }}" placeholder="Motif"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                    </div>

                    {{-- Statut --}}
                    <div class="relative" data-dropdown="statut">
                        <input type="hidden" name="statut" id="input-statut" value="{{ old('statut', $salaire->statut) }}">
                        <button type="button" id="dropdown-statut-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span id="dropdown-statut-label" class="truncate text-left">
                                {{ old('statut', $salaire->statut) === 'inactif' ? 'Inactif' : 'Actif' }}
                            </span>
                            <svg id="dropdown-statut-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-statut-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50">
                            <button type="button" onclick="selectDropdown('statut', 'actif', 'Actif')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Actif
                            </button>
                            <button type="button" onclick="selectDropdown('statut', 'inactif', 'Inactif')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition border-t border-slate-100 flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                                Inactif
                            </button>
                        </div>
                    </div>

                    {{-- Commentaire --}}
                    <div class="relative">
                        <div class="absolute left-4 top-3.5 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <textarea name="commentaire" rows="2" placeholder="Commentaire..."
                            class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition resize-none">{{ old('commentaire', $salaire->commentaire) }}</textarea>
                    </div>

                </div>

                <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold uppercase tracking-wider hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-300/50 transition shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5">
                    Enregistrer les modifications
                </button>

                <div class="text-center mt-4">
                    <button type="button"
                        onclick="if(confirm('⚠️ Supprimer définitivement ce salaire ?')) { document.getElementById('delete-form').submit(); }"
                        class="text-xs text-red-500 hover:text-red-700 hover:underline transition">
                        Supprimer ce salaire
                    </button>
                </div>
            </form>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#dbeafe" opacity="0.6"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#a5b4fc" opacity="0.7"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#6366f1" opacity="0.8"/></svg>
            </div>
        </div>
    </div>

    <form id="delete-form" action="{{ route('gestion.salaires.destroy', $salaire) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

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
        if (isHidden) { menu.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
    };
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('dropdown-statut-btn');
        if (btn) btn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown('statut'); });
        document.addEventListener('click', () => {
            document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
        });
        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.addEventListener('click', e => e.stopPropagation()));
    });
</script>
@endpush
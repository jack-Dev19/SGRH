@extends('layouts.dashboard')

@section('title', 'Nouvelle présence')

@section('content')

<div class="max-w-6xl mx-auto py-4">

    <a href="{{ route('gestion.presences.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux présences
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        {{-- CARTE ILLUSTRATION --}}
        <div class="relative bg-purple-600 rounded-[2rem] shadow-2xl shadow-purple-500/30 overflow-hidden min-h-[640px] flex flex-col lg:sticky lg:top-24">
            <div class="relative z-10 px-10 pt-12 text-center">
                <h2 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">Nouvelle Présence</h2>
                <p class="text-sm text-purple-200 uppercase tracking-widest">Pointer un employé</p>
            </div>

            <div class="relative z-10 flex-1 px-10 py-12 flex items-center justify-center">
                <div class="text-center w-full">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-lg mb-2">Enregistrer une présence</p>
                    <p class="text-purple-100 text-sm leading-relaxed max-w-xs mx-auto">
                        Pointez l'arrivée ou le départ d'un employé.
                    </p>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.3"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#d8b4fe" opacity="0.5"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7"/></svg>
            </div>
        </div>

        {{-- FORMULAIRE --}}
        <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-purple-500/20 overflow-hidden min-h-[640px] flex flex-col">
            <form action="{{ route('gestion.presences.store') }}" method="POST" class="relative z-10 px-8 pt-10 pb-32 flex-1">
                @csrf

                <div class="text-center mb-6">
                    <h2 class="text-3xl font-extrabold text-purple-700 uppercase tracking-wider mb-2">Créer</h2>
                    <p class="text-sm text-purple-400 uppercase tracking-widest">Une présence</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-2xl mb-5">
                        @foreach ($errors->all() as $error) <p>• {{ $error }}</p> @endforeach
                    </div>
                @endif

                <div class="space-y-3">

                    {{-- Employé --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <select name="employe_id" required
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition appearance-none">
                            <option value="">— Sélectionner un employé —</option>
                            @foreach($employes as $emp)
                                <option value="{{ $emp->id }}" {{ old('employe_id') === $emp->id ? 'selected' : '' }}>
                                    {{ $emp->nom_complet }} ({{ $emp->matricule }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="date" name="date" required value="{{ old('date', now()->format('Y-m-d')) }}"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                    </div>

                    {{-- Heures entrée / sortie --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            </div>
                            <input type="time" name="heure_entree" value="{{ old('heure_entree', '08:00') }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="time" name="heure_sortie" value="{{ old('heure_sortie', '17:00') }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Statut --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <select name="statut" required
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition appearance-none">
                            <option value="present" {{ old('statut', 'present') === 'present' ? 'selected' : '' }}>Présent</option>
                            <option value="absent" {{ old('statut') === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="retard" {{ old('statut') === 'retard' ? 'selected' : '' }}>Retard</option>
                            <option value="conge" {{ old('statut') === 'conge' ? 'selected' : '' }}>Congé</option>
                        </select>
                    </div>

                    {{-- Retard + Heures travaillées --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="number" name="retard_minutes" min="0" value="{{ old('retard_minutes', 0) }}" placeholder="Retard (min)"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="number" step="0.1" min="0" max="24" name="heures_travaillees" value="{{ old('heures_travaillees', 8) }}" placeholder="Heures"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Commentaire --}}
                    <div class="relative">
                        <div class="absolute left-4 top-3.5 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <textarea name="commentaire" rows="2" placeholder="Commentaire (optionnel)..."
                            class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition resize-none">{{ old('commentaire') }}</textarea>
                    </div>

                </div>

                <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold uppercase tracking-wider hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300/50 transition shadow-lg shadow-purple-500/30 transform hover:-translate-y-0.5">
                    Enregistrer la présence
                </button>
            </form>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.6"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#9333ea" opacity="0.8"/></svg>
            </div>
        </div>
    </div>
</div>

@endsection
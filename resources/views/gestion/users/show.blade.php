@extends('layouts.dashboard')

@section('title', $user->name)

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.users.index') }}" class="hover:text-purple-700 transition-colors">Utilisateurs</a>
        <span>/</span>
        <span class="text-purple-900 font-bold truncate">{{ $user->name }}</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">
        
        {{-- En-tête avec Avatar --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            {{-- Avatar --}}
            <div class="mx-auto w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-white font-extrabold text-2xl">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
            </div>

            <h1 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h1>
            <p class="text-sm text-slate-500">{{ $user->email }}</p>

            {{-- Badges d'en-tête (Rôle + Statut) --}}
            <div class="mt-4 flex items-center justify-center gap-2 flex-wrap">
                @php
                    $badgeRole = match($user->role?->nom) {
                        'Administrateur'      => 'bg-red-50 text-red-700 ring-red-200',
                        'Responsable RH'      => 'bg-purple-50 text-purple-700 ring-purple-200',
                        'Chef de département' => 'bg-blue-50 text-blue-700 ring-blue-200',
                        'Employé'             => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                        default               => 'bg-slate-100 text-slate-600 ring-slate-200',
                    };
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $badgeRole }}">
                    {{ $user->role?->nom ?? 'Aucun rôle' }}
                </span>

                @if($user->statut == 1)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 text-xs font-bold text-emerald-700 shadow-sm ring-1 ring-inset ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Actif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 text-xs font-bold text-red-700 shadow-sm ring-1 ring-inset ring-red-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        Inactif
                    </span>
                @endif
            </div>
        </div>

        {{-- Corps (Informations) --}}
        <div class="px-8 pb-10 relative z-10">
            
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">i</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Informations du compte</h3>
            </div>

            {{-- Grille d'informations --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                
                {{-- Nom complet --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Nom complet</p>
                    </div>
                    <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                </div>

                {{-- Email --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Email</p>
                    </div>
                    <p class="text-sm text-slate-800 truncate">{{ $user->email }}</p>
                </div>

                {{-- Matricule lié --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Matricule lié</p>
                    </div>
                    @if($user->matricule)
                        <span class="inline-flex items-center rounded-md bg-white px-2 py-1 text-xs font-mono font-medium text-slate-700 ring-1 ring-inset ring-slate-200">
                            {{ $user->matricule }}
                        </span>
                    @else
                        <p class="text-sm text-slate-400 italic">— Non lié</p>
                    @endif
                </div>

                {{-- Date de création --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Créé le</p>
                    </div>
                    <p class="text-sm font-medium text-slate-800">{{ $user->created_at?->format('d/m/Y à H:i') }}</p>
                </div>

                {{-- Employé lié (si existant) --}}
                @if($user->employe)
                    <div class="md:col-span-2 rounded-2xl bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-100/50 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm">
                                    <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Employé lié</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $user->employe->nom_complet }}</p>
                                </div>
                            </div>
                            <a href="{{ route('gestion.employes.show', $user->employe) }}"
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-600 bg-white hover:bg-purple-50 px-3 py-2 rounded-lg shadow-sm transition">
                                Voir
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                
                <a href="{{ route('gestion.users.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour à la liste
                </a>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    
                    {{-- Supprimer --}}
                    <form action="{{ route('gestion.users.destroy', $user) }}" method="POST" class="flex-1 md:flex-initial"
                        onsubmit="return confirm('Voulez-vous vraiment supprimer ce compte ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 text-sm font-bold px-5 py-3 rounded-full transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Supprimer
                        </button>
                    </form>

                    {{-- Modifier --}}
                    <a href="{{ route('gestion.users.edit', $user) }}"
                        class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifier
                    </a>
                </div>
            </div>

        </div>

        {{-- Vagues décoratives --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none overflow-hidden h-40">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full">
                <path fill="rgba(167, 139, 250, 0.2)" d="M0,100 C150,150 350,50 500,100 L500,150 L0,150 Z"></path>
                <path fill="rgba(139, 92, 246, 0.3)" d="M0,120 C150,170 350,70 500,120 L500,150 L0,150 Z"></path>
                <path fill="rgba(124, 58, 237, 0.15)" d="M0,140 C150,190 350,90 500,140 L500,150 L0,150 Z"></path>
            </svg>
        </div>

    </div>
</div>

@endsection
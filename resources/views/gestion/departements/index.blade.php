@extends('layouts.dashboard')

@section('title', 'Départements')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Départements</h1>
            <p class="text-sm text-slate-500">{{ $departements->total() }} département(s) enregistré(s)</p>
        </div>

        <a href="{{ route('gestion.departements.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau département
        </a>
    </div>

    {{-- Barre de recherche --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.departements.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[280px]">
                <div class="relative group">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un département..."
                        class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                </div>
            </div>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-full transition shadow-lg shadow-purple-600/30">
                Rechercher
            </button>

            @if(request()->has('search'))
                <a href="{{ route('gestion.departements.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Grille de cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($departements as $dept)
            @php
                $palette = [
                    ['gradient' => 'from-blue-500 to-blue-600',       'bg' => 'bg-blue-100',    'text' => 'text-blue-700'],
                    ['gradient' => 'from-violet-500 to-violet-600',   'bg' => 'bg-violet-100',  'text' => 'text-violet-700'],
                    ['gradient' => 'from-red-500 to-red-600',         'bg' => 'bg-red-100',     'text' => 'text-red-700'],
                    ['gradient' => 'from-emerald-500 to-emerald-600', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                    ['gradient' => 'from-orange-500 to-orange-600',   'bg' => 'bg-orange-100',  'text' => 'text-orange-700'],
                    ['gradient' => 'from-slate-600 to-slate-700',     'bg' => 'bg-slate-100',   'text' => 'text-slate-700'],
                    ['gradient' => 'from-pink-500 to-pink-600',       'bg' => 'bg-pink-100',    'text' => 'text-pink-700'],
                    ['gradient' => 'from-cyan-500 to-cyan-600',       'bg' => 'bg-cyan-100',    'text' => 'text-cyan-700'],
                ];
                $idx = crc32($dept->id) % count($palette);
                $c = $palette[$idx];
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-purple-200 hover:-translate-y-1 transition-all duration-300">

                {{-- Bandeau --}}
                <div class="relative h-24 bg-gradient-to-br {{ $c['gradient'] }} overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>

                    {{-- Badge statut --}}
                    <div class="absolute top-3 right-3">
                        @if($dept->statut === 'actif')
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                <span class="w-1 h-1 rounded-full bg-emerald-300 animate-pulse"></span>
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                Inactif
                            </span>
                        @endif
                    </div>

                    {{-- Icône --}}
                    <div class="absolute bottom-3 left-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="p-5">

                    <h3 class="font-bold text-slate-900 text-base leading-tight mb-1 truncate">
                        {{ $dept->nom }}
                    </h3>

                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 h-8">
                        {{ $dept->description ?: 'Aucune description' }}
                    </p>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="{{ $c['bg'] }} rounded-lg p-2.5 text-center">
                            <p class="text-lg font-extrabold {{ $c['text'] }}">{{ $dept->employes_count }}</p>
                            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Employés</p>
                        </div>
                        <div class="{{ $c['bg'] }} rounded-lg p-2.5 text-center">
                            <p class="text-lg font-extrabold {{ $c['text'] }}">{{ $dept->postes_count }}</p>
                            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Postes</p>
                        </div>
                    </div>

                    {{-- Responsable --}}
                    @if($dept->responsable)
                        <div class="flex items-center gap-2 mb-4 pb-4 border-b border-slate-100">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-[10px] shrink-0">
                                {{ $dept->responsable->initiales }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">Responsable</p>
                                <p class="text-xs font-semibold text-slate-700 truncate">
                                    {{ $dept->responsable->nom_complet }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 mb-4 pb-4 border-b border-slate-100">
                            <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wider">Responsable</p>
                                <p class="text-xs text-slate-400 italic">Non assigné</p>
                            </div>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('gestion.departements.show', $dept) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Voir
                        </a>
                        <a href="{{ route('gestion.departements.edit', $dept) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>
                        <form action="{{ route('gestion.departements.destroy', $dept) }}" method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce département ?');" class="flex-1">
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
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-slate-500 font-medium mb-1">Aucun département trouvé</p>
                <p class="text-slate-400 text-sm mb-4">Commencez par créer un département.</p>
                <a href="{{ route('gestion.departements.create') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nouveau département
                </a>
            </div>
        @endforelse
    </div>

    @if($departements->hasPages())
        <div class="mt-6">{{ $departements->links() }}</div>
    @endif
</div>

@endsection
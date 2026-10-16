@extends('layouts.dashboard')

@section('title', 'Mon équipe')

@section('content')

<div class="max-w-7xl mx-auto">

    @if(!$departement)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-6 text-center">
            <p class="font-bold">Vous n'êtes responsable d'aucun département.</p>
        </div>
    @else

        {{-- En-tête --}}
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Mon équipe</h1>
            <p class="text-sm text-slate-500">
                {{ $departement->nom }} — <span class="font-bold">{{ $membres->total() }}</span> membre(s)
            </p>
        </div>

        {{-- Grille --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

            @forelse($membres as $membre)
                @php
                    $palettes = [
                        ['gradient' => 'from-blue-500 to-blue-600',       'bg' => 'bg-blue-100',    'text' => 'text-blue-700'],
                        ['gradient' => 'from-violet-500 to-violet-600',   'bg' => 'bg-violet-100',  'text' => 'text-violet-700'],
                        ['gradient' => 'from-emerald-500 to-emerald-600', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                        ['gradient' => 'from-orange-500 to-orange-600',   'bg' => 'bg-orange-100',  'text' => 'text-orange-700'],
                        ['gradient' => 'from-pink-500 to-pink-600',       'bg' => 'bg-pink-100',    'text' => 'text-pink-700'],
                        ['gradient' => 'from-cyan-500 to-cyan-600',       'bg' => 'bg-cyan-100',    'text' => 'text-cyan-700'],
                    ];
                    $idx = crc32($membre->id) % count($palettes);
                    $c = $palettes[$idx];
                @endphp

                <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 hover:-translate-y-1 transition-all duration-300">

                    {{-- Bandeau --}}
                    <div class="relative h-20 bg-gradient-to-br {{ $c['gradient'] }}">
                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/10 rounded-full"></div>

                        {{-- Badge statut --}}
                        <div class="absolute top-3 right-3">
                            @if($membre->statut === 'actif')
                                <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    Inactif
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Avatar à cheval --}}
                    <div class="relative">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2">
                            <div class="w-16 h-16 rounded-full bg-white ring-4 ring-white shadow-lg flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-lg">
                                    {{ $membre->initiales }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contenu --}}
                    <div class="pt-10 px-4 pb-4 text-center">
                        <h3 class="font-bold text-slate-900 text-sm leading-tight mb-1 truncate">
                            {{ $membre->nom_complet }}
                        </h3>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-3 font-mono">
                            {{ $membre->matricule }}
                        </p>

                        <div class="{{ $c['bg'] }} rounded-lg p-2 mb-3">
                            <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">Poste</p>
                            <p class="text-xs font-bold {{ $c['text'] }} truncate">
                                {{ $membre->poste?->nom ?? '—' }}
                            </p>
                        </div>

                        <div class="text-left space-y-1.5 pt-3 border-t border-slate-100">
                            <div class="flex items-center gap-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-slate-600 truncate">{{ $membre->email }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-slate-600">{{ $membre->telephone ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <p class="text-slate-500">Aucun membre dans votre équipe.</p>
                </div>
            @endforelse
        </div>

        @if($membres->hasPages())
            <div class="mt-6">{{ $membres->links() }}</div>
        @endif
    @endif

</div>

@endsection
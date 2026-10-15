@extends('layouts.dashboard')

@section('title', 'Mes fiches de paie')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Mes fiches de paie</h1>
        <p class="text-sm text-slate-500">{{ $bulletins->total() }} bulletin(s) disponible(s)</p>
    </div>

    {{-- Grille de cartes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($bulletins as $bulletin)
            @php
                $moisFr = [
                    '01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril',
                    '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août',
                    '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre',
                ];
                $mois = $moisFr[$bulletin->periode] ?? $bulletin->periode;

                $palettes = [
                    ['gradient' => 'from-blue-500 to-blue-600',       'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-100'],
                    ['gradient' => 'from-violet-500 to-violet-600',   'bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'border' => 'border-violet-100'],
                    ['gradient' => 'from-emerald-500 to-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100'],
                    ['gradient' => 'from-orange-500 to-orange-600',   'bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'border' => 'border-orange-100'],
                    ['gradient' => 'from-pink-500 to-pink-600',       'bg' => 'bg-pink-50',    'text' => 'text-pink-700',    'border' => 'border-pink-100'],
                    ['gradient' => 'from-cyan-500 to-cyan-600',       'bg' => 'bg-cyan-50',    'text' => 'text-cyan-700',    'border' => 'border-cyan-100'],
                ];
                $idx = crc32($bulletin->id) % count($palettes);
                $c = $palettes[$idx];
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">

                {{-- Bandeau --}}
                <div class="relative h-24 bg-gradient-to-br {{ $c['gradient'] }}">
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/10 rounded-full"></div>

                    {{-- Badge statut --}}
                    <div class="absolute top-3 right-3 z-10">
                        @if($bulletin->statut === 'validee')
                            <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-300"></span>
                                </span>
                                Validée
                            </span>
                        @elseif($bulletin->statut === 'payee')
                            <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-300"></span>
                                Payée
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-300"></span>
                                {{ ucfirst($bulletin->statut) }}
                            </span>
                        @endif
                    </div>

                    {{-- Mois + Année --}}
                    <div class="absolute bottom-3 left-5">
                        <p class="text-white/80 text-[10px] font-semibold uppercase tracking-wider">Période</p>
                        <p class="text-white text-lg font-extrabold leading-none">
                            {{ $mois }} {{ $bulletin->annee }}
                        </p>
                    </div>
                </div>

                {{-- Icône à cheval --}}
                <div class="relative">
                    <div class="absolute -top-7 right-5">
                        <div class="w-14 h-14 rounded-2xl bg-white shadow-md ring-4 ring-white flex items-center justify-center transform group-hover:scale-105 transition-transform">
                            <div class="w-full h-full rounded-2xl bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="pt-10 px-5 pb-5 flex-1 flex flex-col">

                    {{-- Salaires --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-3">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Brut</p>
                            <p class="text-sm font-extrabold {{ $c['text'] }} leading-tight truncate">
                                {{ number_format($bulletin->salaire_brut, 0, ',', ' ') }}
                            </p>
                            <p class="text-[9px] text-slate-400">MGA</p>
                        </div>
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-3">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Net</p>
                            <p class="text-sm font-extrabold {{ $c['text'] }} leading-tight truncate">
                                {{ number_format($bulletin->salaire_net, 0, ',', ' ') }}
                            </p>
                            <p class="text-[9px] text-slate-400">MGA</p>
                        </div>
                    </div>

                    {{-- Détails --}}
                    <div class="space-y-1.5 text-xs mb-4 pb-4 border-b border-slate-100">
                        <div class="flex justify-between text-slate-500">
                            <span>Base</span>
                            <span class="font-semibold text-slate-700">{{ number_format($bulletin->salaire_base, 0, ',', ' ') }}</span>
                        </div>
                        @if($bulletin->total_primes > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Primes</span>
                                <span class="font-semibold">+{{ number_format($bulletin->total_primes, 0, ',', ' ') }}</span>
                            </div>
                        @endif
                        @if($bulletin->total_retenues > 0)
                            <div class="flex justify-between text-red-500">
                                <span>Retenues</span>
                                <span class="font-semibold">-{{ number_format($bulletin->total_retenues, 0, ',', ' ') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 mt-auto">
                        <a href="#"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold {{ $c['text'] }} {{ $c['bg'] }} hover:brightness-95 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Télécharger
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-slate-500 font-medium mb-1">Aucun bulletin disponible</p>
                <p class="text-slate-400 text-sm">Vos fiches de paie apparaîtront ici.</p>
            </div>
        @endforelse
    </div>

    @if($bulletins->hasPages())
        <div class="mt-6">{{ $bulletins->links() }}</div>
    @endif
</div>

@endsection